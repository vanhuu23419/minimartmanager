<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CategoryController extends Controller
{
    public function index(Request $req) 
    {
        $gridOptions = [
            'paged' => 1, // current pagination
            'pageSize' => 30, // items per page
            'paginationOffset' => 3,  // nums of pagination button on page
        ];
        $gridOptions = collect($gridOptions)->replace( collect($req->all())->only( array_keys($gridOptions) ));
        
        // Get Categories
        $totalItems = 0;
        $categories = CategoryRepository::find($req->all(), $totalItems);

        return view('category.index', [
            'tableName' => 'Danh mục',
            'pageTitle' => 'Danh sách Danh mục',
            'currentPage' => $gridOptions['paged'],
            'pageSize' => $gridOptions['pageSize'],
            'paginationOffset' => $gridOptions['paginationOffset'],
            'totalItems' => $totalItems,
            'categories' => $categories,
            'router' => 'category'
        ]);
    }

    public function edit($flag, $id = null)
    {
        $viewData = [
            'pageTitle' => 'Tạo mới Danh mục',
            'category' => Category::default(),
            'flag' => $flag
        ];

        if ($flag == 'modify')
        {
            // Get Product's current data
            $viewData['pageTitle'] = 'Chỉnh sửa Sản phẩm';
            $viewData['category'] = Category::where('id', '=', $id)->first();
        }

        return view('category.edit', $viewData);
    }

    public function store(Request $req, $flag, $id = null) 
    {
        $keys = ['name'];
        $model = collect($req->all())->only($keys)->filter(function($e) { return $e != null; });
        $model['id'] = $id;
        
        // Run validation
        $validator = Validator::make($model->toArray(), [
            'name' => 'required|between:6,255',
        ]);
        if ($validator->fails())
        {
            return [
                'status' => 'failed',
                'errors' => $validator->errors()->messages()
            ];
        }

        // Validated
        if($flag == 'modify')
        {
            CategoryRepository::update($model->toArray());
            return [
                'status' => 'success'
            ];
        }
        else if ($flag == 'create')
        {
            $id = CategoryRepository::create($model->toArray());
            return [
                'status' => 'success',
                'categoryId' => $id
            ];
        }
    }

    public function destroy(Request $req) 
    {
        $ids = explode(',', $req->get('ids'));
        CategoryRepository::deleteByIds($ids);
    }
}
