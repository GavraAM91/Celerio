<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Membership;
use App\Models\ReportSales;
use App\Models\SalesDetail;
use Illuminate\Support\Str;
use App\Models\SellingPrice;
// use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Http\Request;
use App\Models\selling_price;
use App\Models\MembershipBenefits;
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
    public function show()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
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
        $validator = FacadesValidator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'membership_id' => 'nullable|exists:memberships,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'total_price' => 'required',
            'tax' => 'required|numeric',
            'total_price_with_discount' => 'nullable',
            'final_price' => 'required',
            'cash_received' => 'required|min:0',
            'change' => 'required|numeric',
            'data' => 'required|array',
            'data.*.product_id' => 'required|exists:products,id',
            'data.*.product_name' => 'required',
            'data.*.quantity' => 'required|integer|min:1',
            'data.*.selling_price' => 'required|numeric|min:0'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        //ambil data tervalidasi
        $validatedData = $validator->validate();
        $membershipId = $validatedData['membership_id'] ?? null;
        $finalPrice = $validatedData['final_price'];
        $couponId = $validatedData['coupon_id'] ?? null;

        // Cek Membership dan Tambahkan Poin
        $pointsEarned = 0;

        if ($membershipId) {
            $membershipInfo = Membership::where('id', $membershipId)->first();

            if ($membershipInfo && in_array($membershipInfo->type, ['type1', 'type2'])) {
                $pointsEarned = $finalPrice * 0.02;
                $membershipInfo->point += $pointsEarned;
                $membershipInfo->save();
            }
        }

        if ($couponId) {
            $couponInfo = Coupon::where('id', $couponId)->first();

            $couponInfo->used_coupon += 1;
            $couponInfo->save();
        }

        // Generate Invoice
        $invoiceSales = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Simpan ke Report Sales
        $sales = ReportSales::create([
            'user_id' => Auth::id(),
            'membership_id' => $membershipId,
            'coupon_id' => $validatedData['coupon_id'] ?? null,
            'invoice_sales' => $invoiceSales,
            'tax' => $validatedData['tax'],
            'total_product_price' => $validatedData['total_price'],
            'total_price_discount' => $validatedData['total_price_with_discount'],
            'final_price' => $finalPrice,
            'cash_received' => $validatedData['cash_received'],
            'change' => $validatedData['change'],
        ]);

        // Simpan Detail Penjualan
        foreach ($validatedData['data'] as $item) {
            SalesDetail::create([
                'sales_id' => $sales->id,
                'product_id' => $item['product_id'],
                'invoice_sales' => $invoiceSales,
                'quantity' => $item['quantity'],
                'selling_price' => $item['selling_price'],
            ]);

            //update stock
            $productData = Product::where('id', $item['product_id'])->first();
            $productData->sold_product += $item['quantity'];
            $productData->save();
        }


        activity()->log(Auth::user()->name . 'has doing transaction with code ' . $invoiceSales);

        // Response JSON dengan PDF link
        return response()->json([
            'message' => 'Transaksi berhasil',
            'invoice' => $invoiceSales,
            'points_earned' => $pointsEarned,
        ], 201);
    }

    public function pdfReceipt($invoice_sales)
    {
        //search by invoice sales
        $data_sales = ReportSales::where('invoice_sales', $invoice_sales)->firstOrFail();

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
