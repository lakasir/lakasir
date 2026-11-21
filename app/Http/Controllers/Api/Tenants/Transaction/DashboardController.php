<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Services\Tenants\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function totalRevenue(Request $request)
    {
        [$start, $end, $previousStart, $previousEnd] = $this->windows($request);

        $revenue = $this->dashboard->revenue($start, $end);
        $previous = $this->dashboard->revenue($previousStart, $previousEnd);

        return $this->buildResponse()
            ->setData([
                'total_revenue' => $revenue,
                // The misspelled keys are what clients already read; the
                // correctly spelled ones are there for new code.
                'total_prevous_revenue' => $previous,
                'total_previous_revenue' => $previous,
                'percentage_change' => $this->dashboard->percentageChange($revenue, $previous),
            ])
            ->present();
    }

    public function totalGrossProfit(Request $request)
    {
        [$start, $end, $previousStart, $previousEnd] = $this->windows($request);

        $grossProfit = $this->dashboard->grossProfit($start, $end);
        $previous = $this->dashboard->grossProfit($previousStart, $previousEnd);

        return $this->buildResponse()
            ->setData([
                'total_gross_profit' => $grossProfit,
                'total_prevous_gross_profit' => $previous,
                'total_previous_gross_profit' => $previous,
                'percentage_change' => $this->dashboard->percentageChange($grossProfit, $previous),
            ])
            ->present();
    }

    public function totalSales(Request $request)
    {
        [$start, $end, $previousStart, $previousEnd] = $this->windows($request);

        $sales = $this->dashboard->sales($start, $end);
        $previous = $this->dashboard->sales($previousStart, $previousEnd);

        return $this->buildResponse()
            ->setData([
                'total_sales' => $sales,
                'total_prevous_sales' => $previous,
                'total_previous_sales' => $previous,
                'percentage_change' => $this->dashboard->percentageChange($sales, $previous),
            ])
            ->present();
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: Carbon, 3: Carbon} current window, then the one before it
     */
    private function windows(Request $request): array
    {
        $filterType = $request->filter_type;
        $timezone = $request->timezone ?: config('app.timezone');

        [$start, $end] = $this->getDateRange($filterType, $timezone);

        return [$start, $end, ...$this->previousRange($start, $filterType)];
    }

    /**
     * The same window one period earlier, so a monthly view compares with the
     * previous month and not with part of it.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function previousRange(Carbon $start, ?string $filterType): array
    {
        $start = $start->copy();
        $end = match ($filterType) {
            'yesterday' => $start->copy()->subDay()->endOfDay(),
            'this_week', 'last_week' => $start->copy()->subWeek()->endOfWeek(),
            'this_month', 'last_month' => $start->copy()->subMonth()->endOfMonth(),
            'this_year', 'last_year' => $start->copy()->subYear()->endOfYear(),
            default => $start->copy()->subDay()->endOfDay(),
        };

        $previousStart = match ($filterType) {
            'yesterday' => $start->copy()->subDay()->startOfDay(),
            'this_week', 'last_week' => $start->copy()->subWeek()->startOfWeek(),
            'this_month', 'last_month' => $start->copy()->subMonth()->startOfMonth(),
            'this_year', 'last_year' => $start->copy()->subYear()->startOfYear(),
            default => $start->copy()->subDay()->startOfDay(),
        };

        return [$previousStart, $end];
    }

    /**
     * The selected window in the cashier's time zone, converted to UTC for the
     * comparison against the stored timestamps.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function getDateRange(?string $filterType, string $timezone): array
    {
        $now = now($timezone);

        [$start, $end] = match ($filterType) {
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'this_week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'last_week' => [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'last_month' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'last_year' => [$now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear()],
            default => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
        };

        return [$start->setTimezone('UTC'), $end->setTimezone('UTC')];
    }
}
