<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ReportRepository 
{
    public static function getReport($start, $end)
    {
        $query = 
            DB::table('Receipts')
            ->selectRaw('
                SUM(num_products) as num_products,
                SUM(total_revenue) as total_revenue,
                SUM(total_profit) as total_profit,
                COUNT(1) as num_receipts 
            ')
            ->whereRaw("created_at >= '{$start}' AND created_at <= '{$end}'");

        $data = $query->first();
        return [
            'num_products' => $data->num_products,
            'total_revenue' => $data->total_revenue,
            'total_profit' => $data->total_profit,
            'num_receipts' => $data->num_receipts,
        ];
    }
}