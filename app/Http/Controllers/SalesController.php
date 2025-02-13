<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Membership;
use App\Models\SellingPrice;
use Illuminate\Http\Request;
use App\Models\selling_price;
use App\Models\MembershipBenefits;
// use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('casier.sales.create', ['title => Create']);
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
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }

    //search coupon
    public function searchCoupon(Request $request)
    {
        $coupon_name = $request->query('name');

        $coupon = Coupon::where('name_coupon', $coupon_name)->first();

        if ($coupon) {
            return response()->json([
                'success' => true,
                'data' => $coupon
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Coupon tidak ditemukan'
        ], 404);
    }

    //search product
    public function searchProduct(Request $request)
    {
        $product_name = $request->query('productName');
        $membershipType = $request->query('membershipType');
        // var_dump($product_name);

        $product = Product::where('product_name', 'LIKE', "%$product_name%")->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }

        $selling_price = SellingPrice::where('type_buyer', $membershipType)->first();

        // if (!$selling_price) {
        //     return response()->json(['success' => false, 'message' => 'Membership tidak ditemukan']);
        // }

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'sellingPrice' => $selling_price,
            ],
        ]);
    }

    //search membership
    public function searchMembership(Request $request)
    {

        $membership_code = $request->query('code'); // Ambil dari query string

        $membership = Membership::where('membership_code', $membership_code)->first();

        if ($membership) {
            return response()->json([
                'success' => true,
                'data' => $membership
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    public function PurchasedProduct(Request $request)
    {
        // Validasi semua data
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

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        // Siapkan variabel
        $membershipData = null;
        $membershipBenefit = null;
        $discountMembership = 0;
        $pointMembership = 0;

        // Masukkan data ke variabel
        $totalPrice = $request->total_price;

        try {
            // Jika menggunakan kartu membership
            if ($request->membership_id) {
                // Masukkan membership_id dari request
                $membershipId = $request->membership_id;

                // Cek ke database membership
                $membershipData = Membership::findOrFail($membershipId);

                // Cek ke database membership Benefit
                $membershipBenefit = MembershipBenefits::where('type', $membershipData->type)->firstOrFail();

                // Cek tipe membership
                if ($membershipData->type == 'type1') {
                    $discountMembership = $membershipBenefit->percentageDiscount;
                } elseif ($membershipData->type == 'type2') {
                    $discountMembership = $membershipBenefit->percentageDiscount;
                } elseif ($membershipData->type == 'type3') {
                    $discountMembership = $membershipBenefit->percentageDiscount;
                }
            }

            // Sistem pembelian
            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Masukkan harga produk
                $basePrice = $product->product_price;

                // Cek tipe membership
                if ($membershipData) {
                    $selling_price = SellingPrice::where('type', $membershipData->type)->first();
                    $markup = $selling_price ? $selling_price->markup / 100 : 0.03;
                } else {
                    $markup = 0.03;
                }

                // Hitung harga + margin
                $price = $basePrice + ($basePrice * $markup);

                // Cek jika ada diskon yang ditambahkan
                $discountByCoupon = 0;

                if (!empty($item['coupon_code'])) {
                    $coupon = Coupon::where('coupon_code', $item['coupon_code'])->first();
                    if ($coupon->used_coupon <= $coupon->total_coupon) {
                        // Jika ada kupon yang tersedia, edit tabel
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

                // Perhitungan subtotal
                $subtotal = ($price * $item['quantity']) - $discountByCoupon;

                // Hitung pajak
                $tax = $subtotal * 0.12; // PPN 12%

                // Poin membership
                $points = ($membershipData && in_array($membershipData->type, ['type1', 'type2'])) ? ($subtotal * 0.02) : 0;

                // Total harga
                $totalPrice += $subtotal + $tax;
                $pointMembership += $points;

                // Masukkan ke membership point
                $data_membership = Membership::where('id', $membershipData->id)->update([
                    'points' => $membershipData->points + $pointMembership,
                ]);

                // Membuat invoice penjualan
                $invoiceSales = 'INV-' . time() . '-' . strtoupper(substr($data_membership->username, 0, 5));

                // Masukkan ke penjualan
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
                        ->decrement('stock', $item['quantity']);

                    $product = Product::find($item['product_id']);

                    if ($product->stock <= $product->minimal_stock) {
                        $product->status = 'low stock';
                        $product->save();
                    }
                } else {
                    return response()->json([
                        'message' => 'Gagal menyimpan data penjualan untuk produk ' . $product->name
                    ], 500);
                }

                // Dapatkan username
                $user = User::where('id', $item['user_id'])->first();

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
                    'total' => $totalPrice,
                    'tax' => $tax,
                    'discount' => $discountByCoupon,
                    'points_earned' => $pointMembership
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

        //casier name
        $user_data = User::where('user_id', Auth::user()->id);
        $casier_name = $user_data->username;

        //check membership
        $membership_data = Membership::where('id', $data_sales->membership_id)->first();
        $membership_discount = $membership_data ? $membership_data->discount : 0;
        $membership_text = $membership_data ? number_format($membership_discount, 0, ',', '.') : "Tidak Memakai";

        //discount
        $discount_data = Coupon::where('id', $data_sales->discount_id)->first();
        $discount = $discount_data ? $discount_data->amount : 0;



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
                <h2>' . htmlspecialchars($data_sales->gugugaga) . '</h2>
                <p>Tanggal: ' . htmlspecialchars($data_sales->created_at) . '</p>
                <p>Kasir: ' . htmlspecialchars($casier_name) . '</p>
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
        foreach ($data_sales as $product) {
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
                        <td class="value">Rp ' . number_format($data_sales->quantity,) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Pajak:</td>
                        <td class="value">Rp ' . number_format($data_sales->tax) . '</td>
                    </tr>
                    <tr>
    <td class="label">Diskon:</td>
    <td class="value">Rp ' . number_format($discount, 0, ',', '.') . '</td>
</tr>
<tr>
    <td class="label">Potongan Membership:</td>
    <td class="value">Rp ' . $membership_text . '</td>
</tr>
                    <tr>
                        <td class="label"><b>Total:</b></td>
                        <td class="value"><b>Rp ' . number_format($data_sales->total, 0, ',', '.') . '</b></td>
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
