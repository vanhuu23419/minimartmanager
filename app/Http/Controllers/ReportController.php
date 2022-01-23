<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceiptProduct;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{

    public function getChartData($_from, $_to, $_period)
    {
        /*
        Pre-convert Dates to database Unix Timestamp
        */
        $unixTime = DB::select(DB::raw("
            SELECT UNIX_TIMESTAMP('{$_from}') as unixTimeFrom,
                   UNIX_TIMESTAMP('{$_to}') as unixTimeTo
        "));

        $from = $unixTime[0]->unixTimeFrom;
        $to = $unixTime[0]->unixTimeTo;
        $period = round(($to - $from)/$_period);
        
        /*
        Query to get periods revenue summation
        */
        $query = 
            DB::table("Receipts")
            ->selectRaw("
                CEILING((UNIX_TIMESTAMP(created_at) - {$from})/{$period}) as period,
                FROM_UNIXTIME(
                    CEILING(
                        (UNIX_TIMESTAMP(created_at) - {$from})/{$period}
                    ) * {$period} + {$from}, '%d-%m-%Y'
				) as label, 
                SUM(total_revenue) as revenue
            ")
            ->whereRaw("
                UNIX_TIMESTAMP(created_at) BETWEEN {$from} AND {$to}
            ")
            ->groupBy(['period', 'label'])
            ->get();
        
        
        $missing = $_period - $query->count();
        $chartData = $query->map(function($e) { return $e->revenue; });
        $chartLabels = $query->map(function($e) { return $e->label; });
        if($missing > 0) {
            $chartData = $chartData->merge(array_fill(0, $missing, 0));
            $chartLabels = $chartLabels->merge(array_fill(0, $missing, ''));
        }
        
        /*
        Previous Term Revenue 
        */
        $prevTermStart = $from - ($to - $from);
        $prevTermEnd = $from - 3600*24;

        $prevTermRev = 
            DB::table('Receipts')
            ->selectRaw('
                SUM(total_revenue) as revenue
            ')
            ->whereRaw("
                UNIX_TIMESTAMP(created_at) BETWEEN {$prevTermStart} AND {$prevTermEnd}
            ")
            ->first()
            ->revenue;

        return [
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,  // Week's days revenue
            'reportData' => ReportRepository::getReport($_from, $_to),
            'prevTermRev' => $prevTermRev ?? 0,  // Previous term (week) revenue
        ];
    }

    public function chartReport(Request $req)
    {
        $from = date('Y-m-d', strtotime($req->get('from')));
        $to = date('Y-m-d', strtotime($req->get('to')));
        $period = $req->get('period');

        return $this->getChartData($from, $to, $period);
    }

    protected function bestSellingProducts($from, $to)
    {
        return ReceiptProduct::join('Receipts', 'ReceiptProducts.receipt_id', '=', 'Receipts.id')
        ->selectRaw('
            product_id,
            product_name,
            product_price,
            SUM(quantity) as quantity
        ')
        ->whereRaw("
            Receipts.created_at BETWEEN '{$from}' AND '{$to}'
        ")
        ->groupBy(['product_id', 'product_name', 'product_price'])
        ->orderBy('quantity', 'DESC')
        ->limit(20)
        ->get();
    }

    public function index(Request $req, $time = 'weekReport')
    {
        $today = [ date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time()) ];
        $data = [
            'dayReport' => ReportRepository::getReport($today[0], $today[1]),
            'fromDate' => null,
            'toDate' => null,
            'action' => $time,
        ];
    
        if($time == 'weekReport') {
            $data['fromDate'] = date('d-m-Y', strtotime('this week'));
            $data['toDate'] = date('d-m-Y', strtotime('sunday this week'));
            $data['period'] = 7;
            $data['bestSellingProducts'] = $this->bestSellingProducts(
                date('Y-m-d', strtotime('this week')),
                date('Y-m-d', strtotime('sunday this week'))
            );
        }
        else if ($time == 'monthReport') {
            $data['fromDate'] = date('01-m-Y', time());
            $data['toDate'] = date('t-m-Y', time());
            $data['period'] = 15;
            $data['bestSellingProducts'] = $this->bestSellingProducts(
                date('Y-m-01', time()),
                date('Y-m-t', time())
            );
        }
        else if ($time == 'yearReport') {
            $data['fromDate'] = date('01-01-Y', time());
            $data['toDate'] = date('31-12-Y', time());
            $data['period'] = 12;
            $data['bestSellingProducts'] = $this->bestSellingProducts(
                date('Y-01-01', time()),
                date('Y-12-31', time())
            );
        }
        else if ($time == 'custom') {
            $data['fromDate'] = $req->get('from');
            $data['toDate'] = $req->get('to');
            $amount = (strtotime($data['toDate']) - strtotime($data['fromDate']));
            if ($amount >= 12 * 24 * 3600) {
                $data['period'] = 12;
            }
            else {
                $data['period'] = $amount/(3600*24);
            }
            $data['bestSellingProducts'] = $this->bestSellingProducts(
                date('Y-m-d', strtotime($data['fromDate'])),
                date('Y-m-d', strtotime($data['toDate']))
            );
        }
        
        return view('report.index', $data);
    }
}
