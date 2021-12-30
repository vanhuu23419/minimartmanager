<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ProductController extends Controller
{
    public function index(Request $req) 
    {
        $gridOptions = [
            'paged' => 1, // current pagination
            'pageSize' => 30, // items per page
            'paginationOffset' => 3,  // nums of pagination button on page
            'orderbyOptions' => [         // The possible value for 'orderby'
                'category' => 'Tên danh mục', 
                'price_asc' => 'Giá bán - Tăng dần',
                'price_desc' => 'Giá bán - Giảm dần',
            ],
            'orderby' => 'category',
        ];
        $gridOptions = collect($gridOptions)->replace( collect($req->all())->only( array_keys($gridOptions) ));
        
        // Get Products
        $totalItems = 0;
        $products = ProductRepository::find($req->all(), $totalItems);
        $products = $products->transform( function($i) { 
            $i->thumb_path = asset('storage/'.$i->thumb_path);
            return $i; 
        });

        // Get category name ( category filter )
        $categoryName = 'Nhập danh mục...';
        if( $req->get('categoryId') )
        {
            $categoryName = Category::whereRaw("id = {$req->get('categoryId')}")->first()->name;
        }

        return view('product.index', [
            'tableName' => 'Sản phẩm',
            'pageTitle' => 'Danh sách sản phẩm',
            'currentPage' => $gridOptions['paged'],
            'pageSize' => $gridOptions['pageSize'],
            'paginationOffset' => $gridOptions['paginationOffset'],
            'orderbyOptions' => $gridOptions['orderbyOptions'],
            'orderby' => $gridOptions['orderby'],
            'totalItems' => $totalItems,
            'products' => $products,
            'categoryName' => $categoryName,
            'router' => 'product'
        ]);
    }

    public function edit($flag, $id = null)
    {
        $viewData = [
            'pageTitle' => 'Tạo mới Sản phẩm',
            'product' => Product::default(),
            'w2uiCategoriesList' => [],
            'w2uiProductUnits' => [],
            'flag' => $flag
        ];
        $viewData['w2uiProductUnits'] = ProductUnit::get()->transform(function($e) {
            return ["id" => $e->unit_id, "text" => $e->unit_name];
        })->toArray();

        if ($flag == 'modify')
        {
            // Get Product's current data
            $viewData['pageTitle'] = 'Chỉnh sửa Sản phẩm';
            $viewData['product'] = Product::where('id', '=', $id)->first();
            $viewData['w2uiCategoriesList'] = $viewData['product']->categories->transform(function($e) {
                return ["id" => $e->id, "text" => $e->name];
            })->toArray();
        }

        return view('product.edit', $viewData);
    }

    public function store(Request $req, $flag, $id = null) 
    {
        $keys = ['name', 'cost', 'price', 'quantity', 'unit_id', 'category_ids', 'description'];
        $model = collect($req->all())->only($keys)->filter(function($e) { return $e != null; });
        $model['id'] = $id;
        
        // Run validation
        $validator = Validator::make($model->toArray(), [
            'name' => 'required|between:6,255',
            'description' => 'between:6,255',
            'quantity' => 'gte:0',
            'cost' => 'gte:0',
            'price' => 'gte:0',
            'category_ids' => 'required',
            'unit_id' => 'required',
        ]);
        if ($validator->fails())
        {
            return [
                'status' => 'failed',
                'errors' => $validator->errors()->messages()
            ];
        }

        // Validated
        if($req->hasFile('thumbnail')) 
        {
            // Remove current thumbnail file
            if ($flag == 'modify')
            {
                $currentThumb = Product::whereRaw("id = {$id}")->first()->thumb_path;
                $currentThumb = storage_path('app').'/public/'.$currentThumb;
                File::delete($currentThumb);
            }
            // Save new thumbnail file ( in storage/public/product_img )
            $filePath = $req->file("thumbnail")->store( 'public/product_img' );
            // Set model thumbpath
            $thumbPath = substr( $filePath, strlen('public/')); // remove 'public/' portion for sympolic link
            $model = $model->merge(['thumb_path' => $thumbPath]);
        }
        if($flag == 'modify')
        {
            ProductRepository::update($model->toArray());
            return [
                'status' => 'success'
            ];
        }
        else if ($flag == 'create')
        {
            $id = ProductRepository::create($model->toArray());
            return [
                'status' => 'success',
                'productId' => $id
            ];
        }
    }

    public function destroy(Request $req) 
    {
        $ids = explode(',', $req->get('ids'));
        ProductRepository::deleteByIds($ids);
    }
}
