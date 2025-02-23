<?php


namespace App\Services;

use Carbon\Carbon;
use Modules\Customer\src\Models\Customer;

//use Modules\Vehicle\src\Models\Vehicle;
//use Modules\Workshop\src\Models\WorkOrder;
//use Modules\Finance\src\Models\Invoice;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStatistics()
    {
        return [
            'counts' => 0,
            'revenue' => [
                'monthlyRevenue' => 0, // Add this default value
                'current_month' => 0,
                'last_month' => 0,
                'percentage_change' => 0,
                'monthly_trend' => []
            ],
            'recent' => 0,
            'trends' => 0,
        ];
        return [
            'counts' => $this->getCounts(),
            'revenue' => $this->getRevenueStats(),
            'recent' => $this->getRecentRecords(),
            'trends' => $this->getTrends(),
        ];
    }

    protected function getCounts()
    {
        $lastMonth = Carbon::now()->subMonth();

        return [
            'customers' => [
                'total' => Customer::count(),
                'active' => Customer::where('is_active', true)->count(),
                'new' => Customer::where('created_at', '>=', $lastMonth)->count(),
            ],
//            'vehicles' => [
//                'total' => Vehicle::count(),
//                'active' => Vehicle::where('status', 'active')->count(),
//            ],
//            'work_orders' => [
//                'total' => WorkOrder::count(),
//                'active' => WorkOrder::whereIn('status', ['pending', 'in_progress'])->count(),
//                'completed' => WorkOrder::where('status', 'completed')
//                    ->where('completed_at', '>=', $lastMonth)
//                    ->count(),
//            ],
        ];
    }

    protected function getRevenueStats()
    {
        return [
            'current_month' => [],
            'last_month' => [],
            'percentage_change' => 2,
            'monthly_trend' => [],
        ];
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $lastMonth = Carbon::now()->subMonth();

        $currentMonthRevenue = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        $lastMonthRevenue = Invoice::whereBetween('created_at', [
            $lastMonth->startOfMonth(),
            $lastMonth->endOfMonth()
        ])
            ->sum('total_amount');

        $percentageChange = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        return [
            'current_month' => $currentMonthRevenue,
            'last_month' => $lastMonthRevenue,
            'percentage_change' => round($percentageChange, 2),
            'monthly_trend' => $this->getMonthlyRevenueTrend(),
        ];
    }

    protected function getMonthlyRevenueTrend()
    {
        return [];
        return Invoice::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();
    }

    protected function getRecentRecords()
    {
        return [];
        return [
            'customers' => Customer::with('vehicles')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'work_orders' => WorkOrder::with(['customer', 'vehicle'])
                ->whereIn('status', ['pending', 'in_progress'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'invoices' => Invoice::with('customer')
                ->where('status', 'unpaid')
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get(),
        ];
    }

    protected function getTrends()
    {
        $now = Carbon::now();
        $periods = [];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $periods[] = [
                'start' => $date->startOfMonth(),
                'end' => $date->endOfMonth(),
                'month' => $date->format('F'),
            ];
        }

        $trends = [];

        foreach ($periods as $period) {
            $trends['customers'][] = Customer::whereBetween('created_at', [
                $period['start'],
                $period['end']
            ])->count();

//            $trends['work_orders'][] = WorkOrder::whereBetween('created_at', [
//                $period['start'],
//                $period['end']
//            ])->count();
//
//            $trends['revenue'][] = Invoice::whereBetween('created_at', [
//                $period['start'],
//                $period['end']
//            ])->sum('total_amount');
        }

        return [
            'labels' => collect($periods)->pluck('month'),
            'datasets' => [
                'customers' => $trends['customers'],
                'work_orders' => $trends['work_orders'] ?? [],
                'revenue' => $trends['revenue'] ?? [],
            ],
        ];
    }
}
