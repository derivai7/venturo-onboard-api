<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ReportSaleController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SaleSummaryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/profile', [AuthController::class, 'profile'])->middleware('auth.api');

    Route::get('/users', [UserController::class, 'index'])->middleware('auth.api', 'role:user.view');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth.api', 'role:user.view');
    Route::post('/users', [UserController::class, 'store'])->middleware('auth.api', 'role:user.create');
    Route::put('/users', [UserController::class, 'update'])->middleware('auth.api', 'role:user.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth.api', 'role:user.delete');

    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    Route::get('/customers', [CustomerController::class, 'index'])->middleware('auth.api', 'role:customer.view');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->middleware('auth.api', 'role:customer.view');
    Route::post('/customers', [CustomerController::class, 'store'])->middleware('auth.api', 'role:customer.create');
    Route::put('/customers', [CustomerController::class, 'update'])->middleware('auth.api', 'role:customer.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->middleware('auth.api', 'role:customer.delete');

    Route::get('/categories', [ProductCategoryController::class, 'index'])->middleware('auth.api', 'role:category.view');
    Route::get('/categories/{id}', [ProductCategoryController::class, 'show'])->middleware('auth.api', 'role:category.view');
    Route::post('/categories', [ProductCategoryController::class, 'store'])->middleware('auth.api', 'role:category.create');
    Route::put('/categories', [ProductCategoryController::class, 'update'])->middleware('auth.api', 'role:category.update');
    Route::delete('/categories/{id}', [ProductCategoryController::class, 'destroy'])->middleware('auth.api', 'role:category.delete');

    Route::get('/products', [ProductController::class, 'index'])->middleware('auth.api', 'role:product.view');
    Route::get('/products/{id}', [ProductController::class, 'show'])->middleware('auth.api', 'role:product.view');
    Route::post('/products', [ProductController::class, 'store'])->middleware('auth.api', 'role:product.create');
    Route::put('/products', [ProductController::class, 'update'])->middleware('auth.api', 'role:product.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('auth.api', 'role:product.delete');

    Route::get('/promo', [PromoController::class, 'index'])->middleware('auth.api', 'role:promo.view');
    Route::get('/promo/{id}', [PromoController::class, 'show'])->middleware('auth.api', 'role:promo.view');
    Route::post('/promo', [PromoController::class, 'store'])->middleware('auth.api', 'role:promo.create');
    Route::put('/promo', [PromoController::class, 'update'])->middleware('auth.api', 'role:promo.update');
    Route::delete('/promo/{id}', [PromoController::class, 'destroy'])->middleware('auth.api', 'role:promo.delete');

    Route::get('/vouchers', [VoucherController::class, 'index'])->middleware('auth.api', 'role:voucher.view');
    Route::get('/vouchers/{id}', [VoucherController::class, 'show'])->middleware('auth.api', 'role:voucher.view');
    Route::post('/vouchers', [VoucherController::class, 'store'])->middleware('auth.api', 'role:voucher.create');
    Route::put('/vouchers', [VoucherController::class, 'update'])->middleware('auth.api', 'role:voucher.update');
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->middleware('auth.api', 'role:voucher.delete');

    Route::get('/discounts', [DiscountController::class, 'index'])->middleware('auth.api', 'role:discount.view');
    Route::post('/discounts', [DiscountController::class, 'store'])->middleware('auth.api', 'role:discount.create');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->middleware('auth.api', 'role:discount.delete');

    Route::get('/sale', [SaleController::class, 'index'])->middleware('auth.api', 'role:sale.view');
    Route::post('/sale', [SaleController::class, 'store'])->middleware('auth.api', 'role:sale.create');

    Route::get('/report/sale-promo', [ReportSaleController::class, 'viewSalePromo'])->middleware('auth.api', 'role:sale.view');

    Route::get('/report/sale-transaction', [ReportSaleController::class, 'viewSaleTransaction'])->middleware('auth.api', 'role:sale.view');

    Route::get('/report/sale-menu', [ReportSaleController::class, 'viewSaleCategories'])->middleware('auth.api', 'role:sale.view');
    Route::get('/download/sale-category', [ReportSaleController::class, 'viewSaleCategories'])->middleware('auth.api', 'role:sale.view');

    Route::get('/report/sale-customer', [ReportSaleController::class, 'viewSaleCustomers'])->middleware('auth.api', 'role:sale.view');
    Route::get('/report/sale-customer/{id}/{date}', [ReportSaleController::class, 'showSaleDetailCustomer'])->middleware('auth.api', 'role:sale.view');
    Route::get('/download/sale-customer', [ReportSaleController::class, 'viewSaleCustomers'])->middleware('auth.api', 'role:sale.view');

    Route::get('/report/total-sale/summaries', [SaleSummaryController::class, 'getTotalSummary']);
    Route::get('/report/total-sale/year', [SaleSummaryController::class, 'getDiagramPerYear']);
    Route::get('/report/total-sale/month/{year}', [SaleSummaryController::class, 'getDiagramPerMonth']);
    Route::get('/report/total-sale/day/', [SaleSummaryController::class, 'getDiagramPerCustomDate']);
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
