<?php

use App\Models\Product;
use App\Models\MembershipBenefits;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CasierController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SalesDetailController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\MembershipBenefitsController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('admin', [DashboardController::class, 'dashboardAdmin'])
        ->name('admin')
        ->middleware(['auth', 'verified', 'role:admin']);

    Route::get('casier', [DashboardController::class, 'dashboardCasier'])
        ->name('casier')
        ->middleware(['auth', 'verified', 'role:casier']);
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    //dashboard
    // Route::prefix('dashboard')->name('dashboard.')->group(function () {
    //     Route::get('dashboardAdmin', [DashboardController::class, 'dashboardAdmin'])->name('admin');
    // });

    //Products
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::get('show/{id?}', [ProductController::class, 'show'])->name('show');
        Route::post('update/{id?}', [ProductController::class, 'update'])->name('update');
        Route::get('destroy/{id?}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('trashed', [ProductController::class, 'trashed'])->name('trashed');
        Route::post('restore/{id}', [ProductController::class, 'restore'])->name('restore');
        Route::delete('forceDelete/{id}', [ProductController::class, 'forceDelete'])->name('forceDelete');
        Route::post('checkExpiredProducts', [ProductController::class, 'checkExpiredProducts'])->name('checkExpired');
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
        Route::get('trashed', [CouponController::class, 'trashed'])->name('trashed');
        Route::post('restore/{id}', [CouponController::class, 'restore'])->name('restore');
        Route::delete('forceDelete/{id}', [CouponController::class, 'forceDelete'])->name('forceDelete');
    });

    //membership
    Route::prefix('membership')->name('membership.')->group(function () {
        Route::get('/', [MembershipController::class, 'index'])->name('index');
        Route::get('create', [MembershipController::class, 'create'])->name('create');
        Route::post('store', [MembershipController::class, 'store'])->name('store');
        Route::get('edit/{id}', [MembershipController::class, 'edit'])->name('edit');
        Route::get('show/{id?}', [MembershipController::class, 'view'])->name('show');
        Route::post('update/{id}', [MembershipController::class, 'update'])->name('update');
        Route::get('destroy/{id?}', [MembershipController::class, 'destroy'])->name('destroy');
        Route::get('trashed', [MembershipController::class, 'trashed'])->name('trashed');
        Route::post('restore/{id}', [MembershipController::class, 'restore'])->name('restore');
        Route::delete('forceDelete/{id}', [MembershipController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('indexAdmin', [UserController::class, 'indexAdmin'])->name('indexAdmin');
        Route::get('indexCasier', [UserController::class, 'indexCasier'])->name('indexCasier');
        Route::get('create', [UserController::class, 'createCasier'])->name('createCasier');
        Route::get('createAdmin', [UserController::class, 'createAdmin'])->name('createAdmin');
        Route::post('register', [UserController::class, 'register'])->name('register');
        Route::get('editAdmin/{id}', [UserController::class, 'editAdmin'])->name('editAdmin');
        Route::get('editCasier/{id}', [UserController::class, 'editCasier'])->name('editCasier');
        Route::get('showCasier/{id}', [UserController::class, 'showCasier'])->name('showCasier');
        Route::get('showAdmin/{id}', [UserController::class, 'showAdmin'])->name('showAdmin');
        Route::post('update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('trashed', [UserController::class, 'trashedUser'])->name('trashed');
        Route::post('restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('forceDelete/{id}', [UserController::class, 'forceDelete'])->name('forceDelete');
    });

    //membership benefitsx
    // Route::prefix('membership_benefits')->name('membership_benefits.')->group(function () {
    //     Route::get('/', [MembershipBenefitsController::class, 'index'])->name('index');
    //     Route::get('create', [MembershipBenefitsController::class, 'create'])->name('create');
    //     Route::post('store', [MembershipBenefitsController::class, 'store'])->name('store');
    //     Route::get('edit', [MembershipBenefitsController::class, 'edit'])->name('edit');
    //     Route::get('view/{id?}', [MembershipBenefitsController::class, 'view'])->name('show');
    //     Route::post('update/{id?}', [MembershipBenefitsController::class, 'update'])->name('update');
    //     Route::get('destroy/{id?}', [MembershipBenefitsController::class, 'destroy'])->name('destroy');
    // });

    //sales report
    Route::prefix('sales_report')->name('sales_report.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('index');
        Route::get('show/{id?}', [SalesReportController::class, 'show'])->name('show');
        Route::get('productReport', [SalesReportController::class, 'productReport'])->name('productReport');
        Route::get('exportSales', [SalesReportController::class, 'exportSales'])->name('exportSales');
        Route::get('exportProduct', [SalesReportController::class, 'exportProduct'])->name('exportProduct');
    });

    //category
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryProductController::class, 'index'])->name('index');
        Route::get('create', [CategoryProductController::class, 'create'])->name('create');
        Route::post('store', [CategoryProductController::class, 'store'])->name('store');
        Route::get('edit/{id?}', [CategoryProductController::class, 'edit'])->name('edit');
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
});

Route::middleware(['auth', 'verified', 'role:casier'])->group(function () {
    //dashboard
    // Route::prefix('dashboard')->name('dashboard.')->group(function () {
    //     Route::get('dashboardCasier', [DashboardController::class, 'dashboardCasier'])->name('casier');
    // });

    //sales 
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('create', [SalesController::class, 'create'])->name('create');
        Route::post('store', [SalesController::class, 'store'])->name('store');
        Route::post('PurchasedProduct', [SalesController::class, 'PurchasedProduct'])->name('PurchasedProduct');
        Route::get('searchMembership', [SalesController::class, 'searchMembership'])->name('searchMembership');
        Route::get('searchCoupon', [SalesController::class, 'searchCoupon'])->name('searchCoupon');
        Route::get('searchProduct', [SalesController::class, 'searchProduct'])->name('searchProduct');
        Route::get('DetailTransaction', [SalesController::class, 'DetailTransaction'])->name('DetailTransaction');
        Route::get('pdfReceipt/{invoice_sales}', [SalesController::class, 'pdfReceipt'])->name('pdfReceipt');
    });

    //sales report
    Route::prefix('sales_detail')->name('sales_detail.')->group(function () {
        Route::get('/', [SalesDetailController::class, 'index'])->name('index');
        Route::get('create', [SalesDetailController::class, 'create'])->name('create');
        Route::post('store', [SalesDetailController::class, 'store'])->name('store');
        Route::get('show/{id?}', [SalesDetailController::class, 'view'])->name('show');
        Route::get('destroy/{id?}', [SalesDetailController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
