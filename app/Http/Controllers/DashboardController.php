<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', now()->toDateString())->count();
        $todayRevenue = Order::whereDate('created_at', now()->toDateString())->sum('grand_total');
        
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('grand_total');

        return view('admin.dashboard', compact('todayOrders', 'todayRevenue', 'totalOrders', 'totalRevenue'));
    }
}
