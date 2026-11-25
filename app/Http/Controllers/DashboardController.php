<?php

namespace App\Http\Controllers;

use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', now()->toDateString())->count();
        $todayRevenue = Order::whereDate('created_at', now()->toDateString())->sum('grand_total');

        $totalOrders = Order::count();
        $totalRevenue = Order::sum('grand_total');

        $now = now();
        $salesPeriod = request()->query('period', 'year');
        $salesPeriod = in_array($salesPeriod, ['day', 'month', 'year'], true) ? $salesPeriod : 'year';

        if ($salesPeriod === 'day') {
            $salesTotals = Order::query()
                ->whereDate('created_at', $now->toDateString())
                ->whereRaw('HOUR(created_at) BETWEEN ? AND ?', [8, 22])
                ->selectRaw('HOUR(created_at) as period_key, SUM(grand_total) as revenue')
                ->groupByRaw('HOUR(created_at)')
                ->pluck('revenue', 'period_key');

            $periodKeys = range(8, 22);
            $salesChartLabels = array_map(fn (int $hour): string => sprintf('%02d:00', $hour), $periodKeys);
            $salesChartSubtitle = 'Per jam · ' . $now->format('d/m/Y');
        } elseif ($salesPeriod === 'month') {
            $salesTotals = Order::query()
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->selectRaw('DAY(created_at) as period_key, SUM(grand_total) as revenue')
                ->groupByRaw('DAY(created_at)')
                ->pluck('revenue', 'period_key');

            $periodKeys = range(1, $now->daysInMonth);
            $salesChartLabels = array_map('strval', $periodKeys);
            $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $salesChartSubtitle = 'Per tanggal · ' . $monthNames[$now->month - 1] . ' ' . $now->year;
        } else {
            $salesTotals = Order::query()
                ->whereYear('created_at', $now->year)
                ->selectRaw('MONTH(created_at) as period_key, SUM(grand_total) as revenue')
                ->groupByRaw('MONTH(created_at)')
                ->pluck('revenue', 'period_key');

            $periodKeys = range(1, 12);
            $salesChartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $salesChartSubtitle = 'Per bulan · ' . $now->year;
        }

        $salesChartValues = collect($periodKeys)
            ->map(fn (int $periodKey): float => (float) $salesTotals->get($periodKey, 0))
            ->all();

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'totalOrders',
            'totalRevenue',
            'salesPeriod',
            'salesChartSubtitle',
            'salesChartLabels',
            'salesChartValues',
        ));
    }
}
