<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::with('user')->latest()->get();

        return view('admin.order.index', compact('orders'));
    }

    public function show($id) {
        $order = Order::with('user')->findOrFail($id);

        return view('admin.order.show', compact('order'));
    }
}
