<?php

use App\Models\Product;
use App\Models\MembershipBenefits;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\MembershipBenefitsController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth', 'role:admin'])->group(function () {

//dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//Products
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('create', [ProductController::class, 'create'])->name('create');
    Route::post('store', [ProductController::class, 'store'])->name('store');
    Route::get('edit/{id}', [ProductController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [ProductController::class, 'show'])->name('show');
    Route::post('update/{id?}', [ProductController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [ProductController::class, 'destroy'])->name('destroy');
});

//Coupon
Route::prefix('coupon')->name('coupon.')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('index');
    Route::get('create', [CouponController::class, 'create'])->name('create');
    Route::post('store', [CouponController::class, 'store'])->name('store');
    Route::get('edit/{id}', [CouponController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [CouponController::class, 'show'])->name('show');
    Route::post('update/{id?}', [CouponController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [CouponController::class, 'destroy'])->name('destroy');
});

//membership
Route::prefix('membership')->name('membership.')->group(function () {
    Route::get('/', [MembershipController::class, 'index'])->name('index');
    Route::get('create', [MembershipController::class, 'create'])->name('create');
    Route::post('store', [MembershipController::class, 'store'])->name('store');
    Route::get('edit', [MembershipController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [MembershipController::class, 'view'])->name('show');
    Route::post('update/{id?}', [MembershipController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [MembershipController::class, 'destroy'])->name('destroy');
});

//membership benefitsx
Route::prefix('membership_benefits')->name('membership_benefits.')->group(function () {
    Route::get('/', [MembershipBenefitsController::class, 'index'])->name('index');
    Route::get('create', [MembershipBenefitsController::class, 'create'])->name('create');
    Route::post('store', [MembershipBenefitsController::class, 'store'])->name('store');
    Route::get('edit', [MembershipBenefitsController::class, 'edit'])->name('edit');
    Route::get('view/{id?}', [MembershipBenefitsController::class, 'view'])->name('show');
    Route::post('update/{id?}', [MembershipBenefitsController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [MembershipBenefitsController::class, 'destroy'])->name('destroy');
});

//sales report
Route::prefix('sales_report')->name('sales_report.')->group(function () {
    Route::get('/', [SalesReportController::class, 'index'])->name('index');
    Route::get('create', [SalesReportController::class, 'create'])->name('create');
    Route::post('store', [SalesReportController::class, 'store'])->name('store');
    Route::get('show/{id?}', [SalesReportController::class, 'view'])->name('show');
    Route::get('destroy/{id?}', [SalesReportController::class, 'destroy'])->name('destroy');
});

//category
Route::prefix('category')->name('category.')->group(function () {
    Route::get('/', [CategoryProductController::class, 'index'])->name('index');
    Route::get('create', [CategoryProductController::class, 'create'])->name('create');
    Route::post('store', [CategoryProductController::class, 'store'])->name('store');
    Route::get('edit', [CategoryProductController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [CategoryProductController::class, 'view'])->name('show');
    Route::post('update/{id?}', [CategoryProductController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [CategoryProductController::class, 'destroy'])->name('destroy');
});


//activity log
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('create', [ProductController::class, 'create'])->name('create');
    Route::post('store', [ProductController::class, 'store'])->name('store');
    // Route::get('edit', [ProductController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [ProductController::class, 'view'])->name('show');
    // Route::post('update/{id?}', [ProductController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [ProductController::class, 'destroy'])->name('destroy');
});

//role
Route::prefix('role')->name('role.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('create', [RoleController::class, 'create'])->name('create');
    Route::post('store', [RoleController::class, 'store'])->name('store');
    Route::get('edit', [RoleController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [RoleController::class, 'view'])->name('show');
    Route::post('update/{id?}', [RoleController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [RoleController::class, 'destroy'])->name('destroy');
});

//permissions
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [PermissionsController::class, 'index'])->name('index');
    Route::get('create', [PermissionsController::class, 'create'])->name('create');
    Route::post('store', [PermissionsController::class, 'store'])->name('store');
    Route::get('edit', [PermissionsController::class, 'edit'])->name('edit');
    Route::get('show/{id?}', [PermissionsController::class, 'view'])->name('show');
    Route::post('update/{id?}', [PermissionsController::class, 'update'])->name('update');
    Route::get('destroy/{id?}', [PermissionsController::class, 'destroy'])->name('destroy');
});


// });

Route::middleware(['auth', 'role:casier'])->group(function () {

    //sales 
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('create', [SalesController::class, 'create'])->name('create');
        Route::post('store', [SalesController::class, 'store'])->name('store');
    });

    //sales report
    Route::prefix('sales_report')->name('sales_report.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('index');
        Route::get('create', [SalesReportController::class, 'create'])->name('create');
        Route::post('store', [SalesReportController::class, 'store'])->name('store');
        Route::get('show/{id?}', [SalesReportController::class, 'view'])->name('show');
        Route::get('destroy/{id?}', [SalesReportController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
