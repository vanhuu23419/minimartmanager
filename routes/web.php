<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
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
    return redirect('/category/index');
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

require __DIR__.'/auth.php';
