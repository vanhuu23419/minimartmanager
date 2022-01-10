<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\SellController;
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
    return redirect('/receipt');
});


/**
 * Product
 */
Route::get('/product/index', [ProductController::class, 'index'])->name('product.index');
//->middleware(['auth', 'authorize.route'])->name( Claims::ProductIndex );
Route::get('/product/edit/{flag}/{id?}', [ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/store/{flag}/{id?}', [ProductController::class, 'store'])->name('product.store');
Route::any('/product/destroy', [ProductController::class, 'destroy'])->name('product.destroy');

/**
 * Category
 */
Route::get('/category/index', [CategoryController::class, 'index'])->name('category.index');
// //->middleware(['auth', 'authorize.route'])->name( Claims::ProductIndex );
Route::get('/category/edit/{flag}/{id?}', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('/category/store/{flag}/{id?}', [CategoryController::class, 'store'])->name('category.store');
Route::any('/category/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');

/**
 * Sell
 */
Route::get('/sell', [SellController::class, 'index'])->name('sell.index');
Route::post('/sell/products', [SellController::class, 'products'])->name('sell.products');
Route::post('/sell/addToReceipt', [SellController::class, 'addToReceipt'])->name('sell.addToReceipt');
Route::post('/sell/saveReceipt', [SellController::class, 'saveReceipt'])->name('sell.saveReceipt');
Route::get('/sell/printReceipt/{id}', [SellController::class, 'printReceipt'])->name('sell.printReceipt');

/**
 * Receipt
 */
Route::get('/receipt', [ReceiptController::class, 'index'])->name('receipt.index');
Route::post('/receipt/destroy', [ReceiptController::class, 'destroy'])->name('receipt.destroy');

require __DIR__.'/auth.php';
