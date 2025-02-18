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
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\selling_price;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MembershipBenefits;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    // public function searchProduct(Request $request)
    // {
    //     $product_name = $request->query('productName');
    //     $membershipType = $request->query('membershipType');

    //     // Cari produk berdasarkan nama dan status aktif
    //     $product = Product::where('product_name', 'LIKE', "%$product_name%")
    //         ->where('product_status', 'active')
    //         ->first();
    //     if (!$product) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data probably out of stock or deleted'
    //         ]);
    //     }
    //     // $product = Product::where('product_name', 'LIKE', "%$product_name%")
    //     //     ->first();

    //     if (!$product) {
    //         return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
    //     }

    //     $selling_price = SellingPrice::where('type_buyer', $membershipType)->first();

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'product' => $product,
    //             'sellingPrice' => $selling_price,
    //         ],
    //     ]);
    // }

    public function searchProduct(Request $request)
    {
        $product_name = $request->query('productName');
        $membershipType = $request->query('membershipType');

        // Cari produk berdasarkan nama dan status aktif
        $product = Product::where('product_name', 'LIKE', "%$product_name%")
            ->where('product_status', 'active')
            ->first();

        // dd($product);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Data probably out of stock or deleted'
            ]);
        }

        // Ambil data stok produk berdasarkan product_code yang memiliki expired_time paling dekat
        $stockProduct = StockProduct::where('product_code', $product->product_code)
            ->whereDate('expired_at', '>=', now())
            ->orderBy('expired_at', 'asc')
            ->first();

        // Ambil harga jual berdasarkan jenis membership
        $selling_price = SellingPrice::where('type_buyer', $membershipType)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'sellingPrice' => $selling_price,
                'stockProduct' => $stockProduct,
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
                'data' => $membership,

            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    public function PurchasedProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'membership_id' => 'nullable|exists:memberships,id',
            'used_points' => 'nullable',
            'coupon_id' => 'nullable|exists:coupons,id',
            'total_price' => 'required|numeric',
            'tax'  => 'required|numeric',
            'total_price_with_discount' => 'nullable|numeric',
            'final_price' => 'required|numeric',
            'cash_received'  => 'required|numeric|min:0',
            'change' => 'required|numeric',
            'data' => 'required|array',
            'data.*.product_code' => 'required|exists:products,product_code',
            'data.*.product_id' => 'required|exists:products,id',
            'data.*.product_name' => 'required',
            'data.*.quantity' => 'required|integer|min:1',
            'data.*.selling_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 400);
        }

        // Ambil data yang sudah tervalidasi
        $validatedData = $validator->validated();

        // Ambil variabel yang dibutuhkan
        $membershipId = $validatedData['membership_id'] ?? null;
        $finalPrice   = $validatedData['final_price'];
        $couponId     = $validatedData['coupon_id'] ?? null;
        $usePoints    = $validatedData['used_points'] ?? 0;


        $membershipUsername = "Non Member";
        $availablePoints = 0;
        if ($membershipId) {
            $membershipInfo = Membership::find($membershipId);
            if ($membershipInfo) {
                $membershipUsername = $membershipInfo->name;
                $availablePoints = $membershipInfo->point;

                // Batasi penggunaan poin agar tidak melebihi jumlah yang tersedia
                $usePoints = min($usePoints, $availablePoints);

                // Kurangi poin dari akun membership
                $membershipInfo->point -= $usePoints;

                // Tambahkan poin baru yang didapat dari final price sebesar 2%
                $pointsEarned = round($finalPrice * 0.02);
                $membershipInfo->point += $pointsEarned;

                $membershipInfo->save();
            }
        }


        if ($couponId) {
            $couponInfo = Coupon::where('id', $couponId)->first();
            $couponInfo->used_coupon += 1;
            $couponInfo->save();
        }

        $invoiceSales = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $sales = ReportSales::create([
            'user_id' => Auth::id(),
            'membership_id' => $membershipId,
            'coupon_id' => $couponId,
            'invoice_sales' => $invoiceSales,
            'membership_name' => $membershipUsername,
            'tax' => $validatedData['tax'],
            'total_product_price' => $validatedData['total_price'],
            'total_price_discount' => $validatedData['total_price_with_discount'],
            'final_price' => $finalPrice,
            'cash_received' => $validatedData['cash_received'],
            'change' => $validatedData['change'],
            'used_point' => $usePoints,
        ]);

        foreach ($validatedData['data'] as $item) {
            // Cek apakah stok mencukupi sebelum melakukan transaksi
            $stockProduct = StockProduct::where('product_code', $item['product_code'])->first();
            if ($stockProduct && $stockProduct->stock < $item['quantity']) {
                return response()->json([
                    'message' => 'Stok tidak mencukupi untuk produk ' . $item['product_name'],
                ], 400);
            }

            SalesDetail::create([
                'sales_id' => $sales->id,
                'product_id' => $item['product_id'],
                'invoice_sales' => $invoiceSales,
                'quantity' => $item['quantity'],
                'selling_price' => $item['selling_price'],
            ]);

            // Update sold_product dan stock di StockProduct
            $stockProduct->sold_product += $item['quantity'];
            $stockProduct->stock -= $item['quantity'];
            $stockProduct->save();
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($sales)
            ->event('transaction')
            ->withProperties([
                'invoice_sales' => $invoiceSales,
                'membership' => $membershipUsername,
                'total_price' => $validatedData['total_price'],
                'final_price' => $finalPrice,
                'cash_received' => $validatedData['cash_received'],
                'change' => $validatedData['change'],
                'tax' => $validatedData['tax'],
                'points_used' => $usePoints,
                'products' => $validatedData['data'],
            ])
            ->log("Admin " . Auth::user()->name . " melakukan transaksi dengan kode {$invoiceSales}.");

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil',
            'data' => [
                'invoice_sales' => $sales->invoice_sales,
                'total_price' => $sales->total_product_price,
                'final_price' => $finalPrice,
                'points_used' => $usePoints,
                'remaining_points' => $membershipInfo->point ?? 0,
            ]
        ], 201);
    }



    public function DetailTransaction(Request $request)
    {
        $invoice_sales = $request->query('invoice_sales');

        if (!$invoice_sales) {
            return redirect()->route('sales.index')->with('error', 'Invoice tidak ditemukan.');
        }

        $data_sales = ReportSales::where('invoice_sales', $invoice_sales)->first();
        if (!$data_sales) {
            return redirect()->route('sales.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('casier.sales.detailTransaction', [
            'data_sales' => $data_sales,
            'invoice_sales' => $invoice_sales // Kirim invoice_sales langsung ke view
        ]);
    }


    public function pdfReceipt($invoice_sales)
    {
        // Ambil data transaksi berdasarkan invoice
        $data_sales = ReportSales::where('invoice_sales', $invoice_sales)->first();
        if (!$data_sales) {
            return response()->json(['message' => 'Transaksi Tidak Ada'], 404);
        }

        // Ambil detail transaksi berdasarkan invoice
        $sales_detail = SalesDetail::where('invoice_sales', $invoice_sales)->get();
        if ($sales_detail->isEmpty()) {
            return response()->json(['message' => 'Transaksi Detail Tidak Ada'], 404);
        }

        // Ambil nama produk berdasarkan product_id
        foreach ($sales_detail as $item) {
            $product = Product::where('id', $item->product_id)->first();
            $item->product_name = $product ? $product->product_name : 'Produk Tidak Diketahui';
        }

        // Ambil nama kasir dari auth 
        $casier_name = Auth::check() ? Auth::user()->name : 'Tidak Diketahui';

        // Ambil data membership jika ada
        $membership = Membership::where('id', $data_sales->membership_id)->first();
        $membership_name = $membership ? $membership->name : '-';
        $membership_points = $membership ? $membership->points : '-';

        // Ambil data kupon jika ada
        $coupon = Coupon::where('id', $data_sales->coupon_id)->first();
        $discount_display = $coupon ? ($coupon->percentage_coupon ? "{$coupon->percentage_coupon}%" : "Rp " . number_format($coupon->value_coupon, 0, ',', '.')) : '-';

        // Format tanggal
        Carbon::setLocale('id');
        $created_at = Carbon::parse($data_sales->created_at)->format('d F Y');

        // Render HTML ke PDF
        $html = view('casier.sales.pdf.receipt', compact(
            'created_at',
            'casier_name',
            'sales_detail',
            'data_sales',
            'discount_display',
            'membership_name',
            'membership_points'
        ))->render();

        // Generate PDF
        $pdf = Pdf::loadHTML($html);

        return $pdf->download("Struk_{$invoice_sales}.pdf");
    }
}
