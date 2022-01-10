<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReceiptRepository;

class ReceiptController extends Controller
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
        $receipts = ReceiptRepository::find($req->all(), $totalItems);
        foreach ($receipts as $r) {
            $r->time = date('d-m-Y H:i',strtotime($r->created_at));
        }

        return view('receipt.index', [
            'tableName' => 'Hóa đơn',
            'pageTitle' => 'Danh sách Hóa đơn',
            'currentPage' => $gridOptions['paged'],
            'pageSize' => $gridOptions['pageSize'],
            'paginationOffset' => $gridOptions['paginationOffset'],
            'totalItems' => $totalItems,
            'receipts' => $receipts,
            'router' => 'receipt'
        ]);
    }

    public function destroy( Request $req ) 
    {
        ReceiptRepository::deleteByIds($req->get('ids'));
    }
}
