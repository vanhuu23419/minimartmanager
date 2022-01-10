<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\DB;

class ReceiptRepository 
{
    public static function find($opt, &$total = null)
    {
        // The query options
        $options = [
            'fromDate' => null,
            'paged' => 1,           // Current page - for pagination calculation
            'pageSize' => 30,      // Nums of products per page - for pagination calculation
        ];
        // Assign request params to options
        $options = collect($options)->replace( collect($opt)->only(array_keys($options)) );

        /* Build the Query */

        $query = 
            DB::table('Receipts')
            ->selectRaw('Receipts.*');
        if ($options['fromDate']) 
        {
            $fromDate = date('Y:m:d H:i:m', strtotime($options['fromDate']));
            $query->whereRaw("created_at >=  '{$fromDate}'");
        }

        // Get result
        if (isset($total)) {
            $sql = $query->toSql();
            $countQuery = DB::select(DB::raw("SELECT COUNT(1) as total FROM ( {$sql} ) as countQuery "));
            $total = $countQuery[0]->total;
        }
        $query = $query
            ->orderByRaw('Receipts.created_at')
            ->skip(($options['paged'] - 1) * $options['pageSize'])
            ->take($options['pageSize']);
        return $query->get();
    }

    public static function deleteByIds($ids)
    {
        if (is_array($ids)) 
        {
            $ids = implode(',', $ids);
        }
        DB::table('Receipts')->whereRaw("id IN ({$ids})")->delete();
        DB::table('ReceiptProducts')->whereRaw("receipt_id IN ({$ids})")->delete();
    }
}