<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Tenants\Selling;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function totalRevenue(Request $request)
    {
        $filterType = $request->filter_type;
        $timezone = $request->timezone;
        $dateRange = $this->getDateRange($filterType, $timezone);

        $totalPrice = Selling::where('created_at', '>=', $dateRange['startDate'])
            ->where('created_at', '<=', $dateRange['endDate'])
            ->sum('total_price');
        $totalCost = Selling::where('created_at', '>=', $dateRange['startDate'])
            ->where('created_at', '<=', $dateRange['endDate'])
            ->sum('total_cost');
        $totalNetPrice = $totalPrice - $totalCost;

        $previousData = $this->calculatePercentageChange(
            $dateRange['startDate'],
            $filterType,
            $totalNetPrice,
            function (Carbon $previous, Carbon $currentDate) {
                return Selling::where('created_at', '>=', $previous)
                    ->where('created_at', '<=', $currentDate)
                    ->sum('total_price') - Selling::where('created_at', '>=', $previous)
                    ->where('created_at', '<=', $currentDate)
                    ->sum('total_cost');
            });

        return $this->buildResponse()
            ->setData([
                'total_revenue' => $totalNetPrice,
                'total_prevous_revenue' => $previousData['previous'],
                'percentage_change' => intval($previousData['percentage']),
            ])
            ->present();
    }

    public function totalGrossProfit(Request $request)
    {
        $filterType = $request->filter_type;
        $timezone = $request->timezone;
        $dateRange = $this->getDateRange($filterType, $timezone);

        $totalGrossProfit = Selling::where('created_at', '>=', $dateRange['startDate'])
            ->where('created_at', '<=', $dateRange['endDate'])
            ->sum('total_price');
        $previousData = $this->calculatePercentageChange(
            $dateRange['startDate'],
            $filterType,
            $totalGrossProfit,
            function (Carbon $previous, Carbon $currentDate) {
                return Selling::where('created_at', '>=', $previous)
                    ->where('created_at', '<=', $currentDate)
                    ->sum('total_price');
            });

        return $this->buildResponse()
            ->setData([
                'total_gross_profit' => $totalGrossProfit,
                'total_prevous_gross_profit' => $previousData['previous'],
                'percentage_change' => intval($previousData['percentage']),
            ])
            ->present();
    }

    public function totalSales(Request $request)
    {
        $filterType = $request->filter_type;
        $timezone = $request->timezone;
        $dateRange = $this->getDateRange($filterType, $timezone);

        $totalSales = Selling::where('created_at', '>=', $dateRange['startDate'])
            ->where('created_at', '<=', $dateRange['endDate'])
            ->count();
        $previousData = $this->calculatePercentageChange(
            $dateRange['startDate'],
            $filterType,
            $totalSales,
            function (Carbon $previous, Carbon $currentDate) {
                return Selling::where('created_at', '>=', $previous)
                    ->where('created_at', '<=', $currentDate)
                    ->count();
            });

        return $this->buildResponse()
            ->setData([
                'total_sales' => $totalSales,
                'total_prevous_sales' => $previousData['previous'],
                'percentage_change' => intval($previousData['percentage']),
            ])
            ->present();
    }

    private function calculatePercentageChange($currentDate, $filterType, $total, $callbackQuery)
    {
        switch ($filterType) {
            case 'yesterday':
                $previousDate = Carbon::parse($currentDate)->subDay();
                break;
            case 'this_week':
                $previousDate = Carbon::parse($currentDate)->subWeek();
                break;
            case 'last_week':
                $previousDate = Carbon::parse($currentDate)->subWeeks(2);
                break;
            case 'this_month':
                $previousDate = Carbon::parse($currentDate)->subMonth();
                break;
            case 'last_month':
                $previousDate = Carbon::parse($currentDate)->subMonths(2);
                break;
            case 'this_year':
                $previousDate = Carbon::parse($currentDate)->subYear();
                break;
            case 'last_year':
                $previousDate = Carbon::parse($currentDate)->subYears(2);
                break;
            default:
                $previousDate = Carbon::parse($currentDate)->subDay();
                break;
        }

        $previous = $callbackQuery($previousDate, $currentDate);

        return [
            'percentage' => $previous ? ($total - $previous) / $previous * 100 : 0,
            'previous' => $previous,
        ];
    }

    private function getDateRange(?string $filteryType, ?string $timezone)
    {
        switch ($filteryType) {
            case 'yesterday':
                $startDate = now()->subDays(1)->startOfDay();
                $endDate = now()->subDays(1)->endOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'last_year':
                $startDate = now()->subYear()->startOfYear();
                $endDate = now()->subYear()->endOfYear();
                break;
            default:
                $startDate = now();
                $endDate = now()->endOfDay();
                break;
        }
        $startDate = Carbon::parse($startDate)->setTimezone($timezone)->startOfDay()->setTimezone('UTC');
        $endDate = Carbon::parse($endDate)->setTimezone($timezone)->endOfDay()->setTimezone('UTC');

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
