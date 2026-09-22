<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\EmployeeController;
use App\Http\Controllers\MasterData\PaymentMethodController;
use App\Http\Controllers\MasterData\ProductCategoryController;
use App\Http\Controllers\MasterData\SalesController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\UomController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'post_login'])->name('post_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix("/dashboard")->middleware([
    // RedirectWeb::class, 
    'auth'
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix("/profile")->group(function () {
        Route::get("/", [AuthController::class, 'profile'])->name('dashboard.profile');
        Route::post("/", [AuthController::class, 'post_profile'])->name('dashboard.profile.post');
    });
    Route::prefix("/change_password")->group(function () {
        Route::get("/", [AuthController::class, 'change_password'])->name('dashboard.change_password');
        Route::post("/", [AuthController::class, 'post_change_password'])->name('dashboard.change_password.post');
    });

    Route::prefix("/product")->group(function () {
        Route::get("/", [ProductController::class, 'index'])->name('dashboard.product.master');
        Route::get("/create", [ProductController::class, 'create'])->name('dashboard.product.master.create');
        Route::post("/store", [ProductController::class, 'store'])->name('dashboard.product.master.store');
        Route::get("/{id}/detail", [ProductController::class, 'show'])->name('dashboard.product.master.detail');
        Route::get("/{id}/edit", [ProductController::class, 'edit'])->name('dashboard.product.master.edit');
        Route::put("/{id}/update", [ProductController::class, 'update'])->name('dashboard.product.master.update');
        Route::delete("/{id}", [ProductController::class, 'destroy'])->name('dashboard.product.master.delete');
        Route::get("/search", [ProductController::class, 'search'])->name('dashboard.product.search');
        Route::prefix("/purchase")->group(function () {
            Route::get("/", [ProductController::class, 'indexPurchase'])->name('dashboard.product.purchase');
            Route::post("/store", [ProductController::class, 'StorePurchase'])->name('dashboard.product.purchase.store');
            Route::get("/{id}/show", [ProductController::class, 'ShowPurchase'])->name('dashboard.product.purchase.show');
            Route::delete("/{id}", [ProductController::class, 'DeleteDetailPurchase'])->name('dashboard.product.purchase.delete.detail');
        });
    });

    Route::prefix("/order")->group(function () {
        Route::get("/", [OrderController::class, 'index'])->name('dashboard.order.list');
        Route::get("/create", [OrderController::class, 'create'])->name('dashboard.order.create');
        Route::post("/store", [OrderController::class, 'store'])->name('dashboard.order.store');
        Route::get("/{id}/show", [OrderController::class, 'showDetailOrder'])->name('dashboard.order.detail');
        Route::get("/{id}/print", [OrderController::class, 'printReceipt'])->name('dashboard.order.print');
    });

    Route::prefix("/finance")->group(function () {
        Route::get("/cash-flow-daily", [CashFlowController::class, 'list_daily'])->name('dashboard.finance.cash-flow-daily');
        Route::get("/cash-flow-monthly", [CashFlowController::class, 'list_monthly'])->name('dashboard.finance.cash-flow-monthly');
    });

    Route::prefix("/master-data")->group(function () {
        Route::prefix("/uom")->group(function () {
            Route::get("/", [UomController::class, 'index'])->name('dashboard.master-data.uom');
            Route::get("/create", [UomController::class, 'create'])->name('dashboard.master-data.uom.create');
            Route::post("/store", [UomController::class, 'store'])->name('dashboard.master-data.uom.store');
            Route::get("/{id}/edit", [UomController::class, 'edit'])->name('dashboard.master-data.uom.edit');
            Route::put("/{id}/update", [UomController::class, 'update'])->name('dashboard.master-data.uom.update');
            Route::delete('/{id}', [UomController::class, 'destroy'])->name('dashboard.master-data.uom.delete');
        });
        Route::prefix("/product_category")->group(function () {
            Route::get("/", [ProductCategoryController::class, 'index'])->name('dashboard.master-data.product-category');
            Route::get("/create", [ProductCategoryController::class, 'create'])->name('dashboard.master-data.product-category.create');
            Route::post("/store", [ProductCategoryController::class, 'store'])->name('dashboard.master-data.product-category.store');
            Route::get("/{id}/edit", [ProductCategoryController::class, 'edit'])->name('dashboard.master-data.product-category.edit');
            Route::put("/{id}/update", [ProductCategoryController::class, 'update'])->name('dashboard.master-data.product-category.update');
            Route::delete('/{id}', [ProductCategoryController::class, 'destroy'])->name('dashboard.master-data.product-category.delete');
        });
        Route::prefix("/supplier")->group(function () {
            Route::get("/", [SupplierController::class, 'index'])->name('dashboard.master-data.supplier');
            Route::get("/create", [SupplierController::class, 'create'])->name('dashboard.master-data.supplier.create');
            Route::post("/store", [SupplierController::class, 'store'])->name('dashboard.master-data.supplier.store');
            Route::get("/{id}/edit", [SupplierController::class, 'edit'])->name('dashboard.master-data.supplier.edit');
            Route::put("/{id}/update", [SupplierController::class, 'update'])->name('dashboard.master-data.supplier.update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('dashboard.master-data.supplier.delete');
        });
        Route::prefix("/sales")->group(function () {
            Route::get("/", [SalesController::class, 'index'])->name('dashboard.master-data.sales');
            Route::get("/create", [SalesController::class, 'create'])->name('dashboard.master-data.sales.create');
            Route::post("/store", [SalesController::class, 'store'])->name('dashboard.master-data.sales.store');
            Route::get("/{id}/edit", [SalesController::class, 'edit'])->name('dashboard.master-data.sales.edit');
            Route::put("/{id}/update", [SalesController::class, 'update'])->name('dashboard.master-data.sales.update');
            Route::delete('/{id}', [SalesController::class, 'destroy'])->name('dashboard.master-data.sales.delete');
        });
        Route::prefix("/customer")->group(function () {
            Route::get("/", [CustomerController::class, 'index'])->name('dashboard.master-data.customer');
            Route::get("/create", [CustomerController::class, 'create'])->name('dashboard.master-data.customer.create');
            Route::post("/store", [CustomerController::class, 'store'])->name('dashboard.master-data.customer.store');
            Route::get("/{id}/edit", [CustomerController::class, 'edit'])->name('dashboard.master-data.customer.edit');
            Route::put("/{id}/update", [CustomerController::class, 'update'])->name('dashboard.master-data.customer.update');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('dashboard.master-data.customer.delete');
        });
        Route::prefix("/employee")->group(function () {
            Route::get("/", [EmployeeController::class, 'index'])->name('dashboard.master-data.employee');
            Route::get("/create", [EmployeeController::class, 'create'])->name('dashboard.master-data.employee.create');
            Route::post("/store", [EmployeeController::class, 'store'])->name('dashboard.master-data.employee.store');
            Route::get("/{id}/edit", [EmployeeController::class, 'edit'])->name('dashboard.master-data.employee.edit');
            Route::put("/{id}/update", [EmployeeController::class, 'update'])->name('dashboard.master-data.employee.update');
            Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('dashboard.master-data.employee.delete');
        });
        Route::prefix("/payment-method")->group(function () {
            Route::get("/", [PaymentMethodController::class, 'index'])->name('dashboard.master-data.payment-method');
            Route::get("/create", [PaymentMethodController::class, 'create'])->name('dashboard.master-data.payment-method.create');
            Route::post("/store", [PaymentMethodController::class, 'store'])->name('dashboard.master-data.payment-method.store');
            Route::get("/{id}/edit", [PaymentMethodController::class, 'edit'])->name('dashboard.master-data.payment-method.edit');
            Route::put("/{id}/update", [PaymentMethodController::class, 'update'])->name('dashboard.master-data.payment-method.update');
            Route::delete('/{id}', [PaymentMethodController::class, 'destroy'])->name('dashboard.master-data.payment-method.delete');
        });
    });

    Route::prefix('/stock-opname')->group(function () {
        Route::get('/', [StockOpnameController::class, 'index'])->name('dashboard.stock-opname.index');
        Route::post('/', [StockOpnameController::class, 'store'])->name('dashboard.stock-opname.store'); // Buat sesi baru

        Route::get('/{id}', [StockOpnameController::class, 'show'])->name('dashboard.stock-opname.show'); // Halaman detail (read-only)

        // Route Workspace & Submit yang sudah dibuat sebelumnya
        Route::get('/{id}/workspace', [StockOpnameController::class, 'workspace'])->name('dashboard.stock-opname.workspace');
        Route::get('/api/scan', [StockOpnameController::class, 'scanBarcode'])->name('dashboard.stock-opname.scan');
        Route::post('/{id}/submit', [StockOpnameController::class, 'submitWorkspace'])->name('dashboard.stock-opname.submit');
    });
});
