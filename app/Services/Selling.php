<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Selling as SellingModel;
use App\Traits\HasParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Selling extends RepositoryAbstract
{
    use HasParent;

    protected string $model = SellingModel::class;

    public function activity(Request $request)
    {
        $selling = $this->query()
                        ->when($request->search, function ($query)
                        {
                            return $query->where('number_transaction', 'LIKE', '%%'.$request->search.'%%');
                        })
                        ->where('user_id', auth()->id())
                        ->orderBy('transaction_date', 'desc')
                        ->limit(30)
                        ->get();

        return $selling;
    }

    public function datatable(Request $request)
    {
        $items = $this->query()->latest()->get();

        return $this->getObjectModel()->table($items);
    }

    public function card()
    {
        $lastMonth = now()->subMonth()->format('m');
        $currentMonth = now()->format('m');

        return $this->query()->select(
            DB::raw('SUM(total_profit) as total_profit'),
            DB::raw('SUM(total_price) as total_price'),
        )->addSelect([
            'total_qty' => $this->query()->select(DB::raw('SUM(total_qty)'))->where('transaction_date', now()->format('Y-m-d')),
            'last_month_selling' => $this->query()->select(DB::raw('SUM(total_qty)'))->whereMonth('transaction_date', $lastMonth),
            'current_month_selling' => $this->query()->select(DB::raw('SUM(total_qty)'))->whereMonth('transaction_date', $currentMonth)
        ])->first();
    }

    public function salesChart(): array
    {
        $currentYear = now()->format('Y');
        $lastYear = now()->subYear()->format('Y');

        $incomeCurrentYear = $this->query()->select(
            DB::raw('SUM(total_price) as total_price'),
            DB::raw("DATE_FORMAT(transaction_date,'%m') as monthKey"),
        )->groupBy('monthKey')->whereYear('transaction_date', $currentYear)->get()->toArray();

        $incomeLastYear = $this->query()->select(
            DB::raw('SUM(total_price) as total_price'),
            DB::raw("DATE_FORMAT(transaction_date,'%m') as monthKey")
        )->groupBy('monthKey')->whereYear('transaction_date', $lastYear)->get()->toArray();

        $month = get_month(3, true);

        $totalIncomeCurrentByMonth = get_wrapper_month();
        $totalIncomeLastByMonth = get_wrapper_month();
        for ($i = 0; $i < count($month); $i++) {
            foreach ($incomeCurrentYear as $key => $value) {
                /* dump($value); */
                if ($value['monthKey'] == $month[$i]['month_key']) {
                    $totalIncomeCurrentByMonth[$i] = $value['total_price'];
                }
            }

            foreach ($incomeLastYear as $key => $value) {
                /* dump($value); */
                if ($value['monthKey'] == $month[$i]['month_key']) {
                    $totalIncomeLastByMonth[$i] = $value['total_price'];
                }
            }
        }

        return [
            'totalIncomeCurrentByMonth' => $totalIncomeCurrentByMonth,
            'totalIncomeLastByMonth' => $totalIncomeLastByMonth,
        ];
    }

}
