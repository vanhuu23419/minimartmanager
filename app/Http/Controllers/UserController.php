<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    //

    public function index(Request $req)
    {
        $gridOptions = [
            'paged' => 1, // current pagination
            'pageSize' => 30, // items per page
            'paginationOffset' => 3,  // nums of pagination button on page
        ];
        $gridOptions = collect($gridOptions)->replace( collect($req->all())->only( array_keys($gridOptions) ));
        
        // Get Users
        $totalItems = 0;
        $users = UserRepository::find($req->all(), $totalItems);

        return view('user.index', [
            'tableName' => 'Tài khoản',
            'pageTitle' => 'Danh sách Tài khoản',
            'currentPage' => $gridOptions['paged'],
            'pageSize' => $gridOptions['pageSize'],
            'paginationOffset' => $gridOptions['paginationOffset'],
            'totalItems' => $totalItems,
            'users' => $users,
            'router' => 'user'
        ]);
    }

    public function edit($flag, $id = null)
    {
        $viewData = [
            'pageTitle' => 'Tạo mới Tài khoản',
            'user' => User::default(),
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
}
