<?php

namespace App\Http\Controllers;

use App\Repositories\Item;
use App\Repositories\Purchasing;
use App\Repositories\Selling;
use App\Repositories\SellingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        get_lang();
        $selling = new Selling();
        $item = new Item();
        $sellingDetail = new SellingDetail();
        $purchasing = new Purchasing();

        $salesChart = $selling->salesChart();

        $totalIncomeCurrentByMonth = $salesChart['totalIncomeCurrentByMonth'];
        $totalIncomeLastByMonth = $salesChart['totalIncomeLastByMonth'];

        $get_month = get_month(3);

        $selling = $selling->card();

        $lastProfit = $selling->last_month_selling;
        $currentProfit = $selling->current_month_selling;
        $profitMargin = 100;
        if ($lastProfit) {
            $profitMargin = ($lastProfit - $currentProfit) / $lastProfit * 100/100;
        }

        $spending = $purchasing->card();

        $items = $item->getNewestOrder();

        return view('app.dashboard.index', compact('selling', 'spending', 'items', 'profitMargin', 'totalIncomeCurrentByMonth', 'totalIncomeLastByMonth', 'get_month'));
    }
}
