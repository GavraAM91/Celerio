<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\selling_price;
use App\Models\MembershipBenefits;
use Illuminate\Routing\Controller;
// use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }

    //if product already buy do this
    public function PurchasedProduct(Request $request)
    {

        //validate all data
        $validator = FacadesValidator::make($request->all(), [
            'user.id' => 'required',
            'membership_id' => 'nullable',
            'payment_method' => 'required|string',
            'total_price' => 'required',
            'cart' => 'required|array',
            'cart.*.membership_id' => 'nullable',
            'cart.*.product_id' => 'required',
            'cart.*.coupon_code' => 'nullable',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.total_price' => 'required',
        ]);

        //if error 
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        //prepare variable
        $membershipData = null;
        $membershipBenefit = null;
        $discountMembership = 0;
        $pointMembership = 0;

        //insert data to variable
        $totalPrice = $request->total_price;

        try {
            //if they use membership card
            if ($request->membership_id) {
                //insert membership_id from request 
                $membershipId = $request->membeship_id;

                //check into db membership 
                $membershipData = Membership::findOrFail($membershipId);

                //check into db membership Benefit
                $membershipBenefit = MembershipBenefits::where('type', $membershipData->type)->firstOrFail();

                //check the type
                if ($membershipData->type == 'type1') {
                    $discountMembership = $membershipBenefit->percentageDiscount;
                } elseif ($membershipData->type == 'type2') {
                    $discountMembership = $membershipBenefit->percentageDiscount;
                } elseif ($membershipData->type == 'type3') {
                    $discountMembership = $membershipBenefit->percentageDiscount;
                }
            }

            //purchase system
            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                //insert product price
                $basePrice = $product->product_price;

                //check the membership type
                if ($membershipData) {
                    $selling_price = selling_price::where('type', $membershipData->type)->first();
                    $markup = $selling_price ? $selling_price->markup / 100 : 0.03;
                } else {
                    $markup = 0.03;
                }

                //counting + margin
                $price = $basePrice + ($basePrice * $markup);

                //check if any discount added
                $discountByCoupon = 0;

                if (!empty($item['coupon_code'])) {
                    $coupon = Coupon::where('coupon_code', $item['coupon_code'])->first();
                    if ($coupon->used_coupon <= $coupon->total_coupon) {
                        //if any coupon available, edit the table
                        $coupon->used_coupon += 1;
                        $coupon->save();

                        if (!empty($coupon->percentage_coupon)) {
                            $discountByCoupon = $coupon->percentage_coupon;
                        } else {
                            $discountByCoupon = $coupon->value_coupon;
                        }
                    } else {
                        $discountByCoupon = 0;
                    }
                }

                //perhitungann 
                $subtotal = ($price * $item['quantity']) - $discountByCoupon;

                //hitung pajak 
                $tax = $subtotal * 0.11;

                //point membership
                $points = ($membershipData && in_array($membershipData->type, ['type1', 'type2'])) ? ($subtotal * 0.02) : 0;

                //total price
                $totalPrice += $subtotal + $tax;
                $pointMembership += $points;

                //insert into membership point 0  
                $data_membership = Membership::where('id', $membershipData->id)->update([
                    'points' => $membershipData->poin + $pointMembership,
                ]);


                //creating sales invoice 
                $invoiceSales = 'INV-' . time() . '-' . strtoupper(substr($data_membership->username, 0, 5));

                //insert into sales 
                $sale = Sales::create([
                    'membership_id' => $item['membership_id'],
                    'product_id' => $item['product_id'],
                    'coupon_id' => $item['coupon_id'],
                    'invoice_sales' => $invoiceSales,
                    'payment_method' => $item['payment_method'],
                    'total_price' => $item['total_price'],
                    'quantity' => $item['quantity']
                ]);

                if ($sale) {
                    Product::where('id', $item['product_id'])
                        ->increment('sold_product', $item['quantity']);

                    $product = Product::find($item['product_id']);

                    if ($product->sold_product >= $product->stock) {
                        $product->status = 'sold out';
                        $product->save();
                    }
                } else {
                    return response()->json([
                        'message' => 'Gagal menyimpan data penjualan untuk produk ' . $product->name
                    ], 500);
                }

                //get username
                $user = User::where('id', $item['user_id']);

                $items[] = [
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }

            return response()->json([
                'message' => 'Transaksi berhasil',
                'total_price' => $totalPrice,
                'points_earned' => $pointMembership,
                'receipt' => [
                    'cashier' => $user->name,
                    'items' => $items,
                    'total' => $totalPrice
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pdfReceipt($invoice_sales)
    {
        //search by invoice sales
        $data_sales = Sales::where('invoice_sales', $invoice_sales)->firstOrFail();

        //check empty
        if ($data_sales->isEmpty()) {
            return response()->json([
                'message' => 'Transaksi Tidak Ada',
            ], 404);
        }

        //set local time
        Carbon::setLocale('id');
        $hari = Carbon::parse($data_sales->created_at)->translatedFormat('1');
        $created_at = Carbon::parse($data_sales->created_at)->format('d F Y');

        $html = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Struk Kasir</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.5; }
            .container { width: 400px; margin: 0 auto; padding: 20px; border: 1px dashed #000; }
            .header { text-align: center; margin-bottom: 20px; }
            .header h2 { margin: 0; font-size: 20px; }
            .header p { margin: 0; font-size: 12px; }
            hr { border: 1px dashed #000; margin: 10px 0; }
            .content table { width: 100%; margin-top: 10px; border-collapse: collapse; }
            .content table td { padding: 5px; vertical-align: top; }
            .totals { margin-top: 20px; }
            .totals table { width: 100%; }
            .totals table td { padding: 5px; }
            .totals table .label { text-align: left; }
            .totals table .value { text-align: right; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h2>' . htmlspecialchars($store_name) . '</h2>
                <p>Tanggal: ' . htmlspecialchars($transaction_date) . '</p>
                <p>Kasir: ' . htmlspecialchars($cashier_name) . '</p>
            </div>
            <hr>

            <!-- Produk -->
            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <td><b>Produk</b></td>
                            <td><b>Quantity</b></td>
                            <td style="text-align:right;"><b>Harga</b></td>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($products as $product) {
                $html .= '
                        <tr>
                            <td>' . htmlspecialchars($product['name']) . '</td>
                            <td>' . htmlspecialchars($product['quantity']) . '</td>
                            <td style="text-align:right;">Rp ' . number_format($product['price'], 0, ',', '.') . '</td>
                        </tr>';
            }
            $html .= '
                    </tbody>
                </table>
            </div>
            <hr>

            <!-- Total -->
            <div class="totals">
                <table>
                    <tr>
                        <td class="label">Jumlah:</td>
                        <td class="value">Rp ' . number_format($subtotal, 0, ',', '.') . '</td>
                    </tr>
                    <tr>
                        <td class="label">Pajak:</td>
                        <td class="value">Rp ' . number_format($tax, 0, ',', '.') . '</td>
                    </tr>
                    <tr>
                        <td class="label">Diskon:</td>
                        <td class="value">Rp ' . number_format($discount, 0, ',', '.') . '</td>
                    </tr>';
            if ($is_member) {
                $html .= '
                    <tr>
                        <td class="label">Potongan Membership:</td>
                        <td class="value">Rp ' . number_format($membership_discount, 0, ',', '.') . '</td>
                    </tr>';
            }
            $html .= '
                    <tr>
                        <td class="label"><b>Total:</b></td>
                        <td class="value"><b>Rp ' . number_format($total, 0, ',', '.') . '</b></td>
                    </tr>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Terima kasih telah berbelanja!</p>
            </div>
        </div>
    </body>
    </html>';
    }
}
