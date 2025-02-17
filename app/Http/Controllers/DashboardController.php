<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Membership;
use App\Models\ReportSales;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboardAdmin(Request $request)
    {
        $product = Product::all()->count();
        $membership = Membership::all()->count();
        $category = CategoryProduct::all()->count();
        // Ambil produk yang akan kedaluwarsa dalam 7 hari
        $expiredSoonProducts = Product::where('expired_at', '<=', now()->addDays(7))
            ->orderBy('expired_at', 'asc')
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

        // Format data penjualan untuk Chart.js
        $salesByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $salesByDay[$date] = 0; // Default 0 jika tidak ada penjualan
        }
        foreach ($salesData as $data) {
            $salesByDay[$data->date] = $data->total_sales;
        }

        return view('admin.dashboard', compact('product', 'membership', 'category', 'expiredSoonProducts', 'salesByDay'));
    }


    public function dashboardCasier(Request $request)
    {
        return view('casier.dashboard');
    }

    
}

