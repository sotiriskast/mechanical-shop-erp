<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Customer\src\Models\Customer;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'month');
        $startDate = $this->getStartDate($dateRange);

        return Inertia::render('Dashboard/Index', [
            'statistics' => [
                'trends' => $this->getCustomerTrends($startDate),
                'counts' => [
                    'customers' => [
                        'total' => Customer::count(),
                        'active' => Customer::where('is_active', true)->count(),
                        'new' => Customer::where('created_at', '>=', $startDate)->count(),
                    ],
                ],
            ],
            'filters' => [
                'date_range' => $dateRange,
            ],
        ]);
    }

    private function getCustomerTrends(Carbon $startDate)
    {
        $labels = [];
        $customerData = [];

        // Generate data points for each day in the range
        for ($date = $startDate->copy(); $date <= now(); $date->addDay()) {
            $labels[] = $date->format('M d');
            $customerData[] = Customer::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                'customers' => $customerData,
            ],
        ];
    }

    private function getStartDate($range)
    {
        return match ($range) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };
    }
}
