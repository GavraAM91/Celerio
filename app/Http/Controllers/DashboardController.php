<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Membership;
use App\Models\ActivityLog;
use App\Models\ReportSales;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboardAdmin(Request $request)
    {
        $productData = Product::all()->count();
        $membershipData = Membership::all()->count();
        $categoryData = CategoryProduct::all()->count();


        // Produk dengan stok rendah
        $lowStockProducts = StockProduct::whereHas('product', function ($query) {
            $query->whereRaw('stock_products.stock <= products.minimum_stock');
        })
            ->orderBy('stock', 'asc')
            ->with('product')
            ->get();

        // dd($lowStockProducts);

        $expiredTodayProducts = StockProduct::whereDate('expired_at', now())
            ->whereNull('deleted_at')
            ->whereHas('product')
            ->orderBy('expired_at', 'asc')
            ->with('product')
            ->get();

        // Produk yang akan kedaluwarsa dalam 7 hari
        $expiredSoonProducts = StockProduct::whereBetween('expired_at', [now(), now()->addDays(7)])
            ->whereNull('deleted_at')  // Tambahkan kondisi untuk deleted_at null
            ->orderBy('expired_at', 'asc')
            ->with('product')
            ->get();

        // Ambil total penjualan per hari selama 7 hari terakhir
        $salesData = ReportSales::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(final_price) as total_sales')
        )
            ->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $salesByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $salesByDay[$date] = 0;
        }
        foreach ($salesData as $data) {
            $salesByDay[$data->date] = $data->total_sales;
        }

        $totalSales7Days = ReportSales::whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()])
            ->sum('final_price');

        return view('admin.dashboard', compact('productData', 'membershipData', 'categoryData', 'lowStockProducts', 'expiredTodayProducts', 'expiredSoonProducts', 'salesByDay', 'totalSales7Days'));
    }


    public function dashboardCasier(Request $request)
    {
        return view('casier.dashboard');
    }

    public function activitylogindex(Request $request)
    {
        // Ambil data activity log dengan pagination (10 per halaman)
        $logs = ActivityLog::with('user') // Ambil relasi user
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.activityLog.index', compact('logs'));
    }
}
