<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::with('user')->latest()->get();

        return view('admin.order.index', compact('orders'));
    }

    public function show($id) {
        $order = Order::with('user')->findOrFail($id);
        $orderItems = OrderItem::with('item')->where('order_id', $order->id)->get();

        return view('admin.order.show', compact('order', 'orderItems'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,settlement,coocked',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
