<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::any('/w2ui/productCategory', function(Request $req) 
{

    $params = json_decode($req->get('request'));
    $search = $params->search ?? '';
    $query = Category::whereRaw("name LIKE '%{$search}%'")->take( $params->max??50);
    $cats = $query->get();

    $sql = $query->toSql();

    $records = $cats->transform(function($e) {
        return [
            'id' => $e->id,
            'text' => $e->name,
        ];
    });

    return [
        'status' => 'success',
        'records' => $records,
    ];

})->name('w2ui.category.get');