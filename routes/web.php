<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\CategoryController;
use App\Http\Requests\Auth\RoleClaims\Claims;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/user');
});


/**
 * Product
 */
Route::get('/product/index', [ProductController::class, 'index'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ProductIndex );
Route::get('/product/edit/{flag}/{id?}', [ProductController::class, 'edit'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ProductEdit );
Route::post('/product/store/{flag}/{id?}', [ProductController::class, 'store'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ProductStore );
Route::any('/product/destroy', [ProductController::class, 'destroy'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ProductDestroy );

/**
 * Category
 */
Route::get('/category/index', [CategoryController::class, 'index'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::CategoryIndex );
Route::get('/category/edit/{flag}/{id?}', [CategoryController::class, 'edit'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::CategoryEdit );
Route::post('/category/store/{flag}/{id?}', [CategoryController::class, 'store'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::CategoryStore );
Route::any('/category/destroy', [CategoryController::class, 'destroy'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::CategoryDestroy );

/**
 * Sell
 */
Route::get('/sell', [SellController::class, 'index'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::SellIndex );
Route::post('/sell/products', [SellController::class, 'products'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::SellProducts );
Route::post('/sell/addToReceipt', [SellController::class, 'addToReceipt'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::SellAddToReceipt );
Route::post('/sell/saveReceipt', [SellController::class, 'saveReceipt'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::SellSaveReceipt );
Route::get('/sell/printReceipt/{id}', [SellController::class, 'printReceipt'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::SellPrintReceipt );

/**
 * Receipt
 */
Route::get('/receipt', [ReceiptController::class, 'index'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ReceiptIndex );
Route::post('/receipt/destroy', [ReceiptController::class, 'destroy'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ReceiptDestroy );

/**
 * Report
 */
Route::get('/report/{time?}', [ReportController::class, 'index'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ReportIndex );
Route::post('/report/chartReport', [ReportController::class, 'chartReport'])
    ->middleware(['auth', 'authorize.route'])->name( Claims::ReportChartReport );

/**
 * User
 */
// Route::get('/user', [UserController::class, 'index'])->name('user.index');
// Route::get('/user/edit/{flag}/{id?}', [UserController::class, 'edit'])->name('user.edit');
// Route::post('/user/store/{flag}/{id?}', [UserController::class, 'store'])->name('user.store');
// Route::any('/user/destroy', [UserController::class, 'destroy'])->name('user.destroy');

require __DIR__.'/auth.php';
