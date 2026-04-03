<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class MenuController extends Controller
{

    // MENU
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');
        if ($tableNumber) {
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('customer.menu', compact('items', 'tableNumber'));
    }

    // CART
    public function cart() {
        $cart = Session::get('cart');
        return view('customer.cart', compact('cart'));
    }

    // ADD TO CART
    public function addToCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu item not found'
                ]);
        }

        $cart = Session::get('cart');

        if(isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'image' => $menu->img,
                'qty' => 1
            ];
        }

        Session::put('cart', $cart);
        return response()->json([
            'status' => 'success',
            'message' => 'Menu item added to cart',
            'cart' => $cart
        ]);
    }

    // UPDATE CART
    public function updateCart(Request $request)
    {
    $itemId = $request->input('id');
    $newQty = (int) $request->input('qty');

    if ($newQty <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Quantity is invalid.'
        ]);
    }

    $cart = Session::get('cart');

    if (isset($cart[$itemId])) {
        $cart[$itemId]['qty'] = $newQty;
        Session::put('cart', $cart);
        Session::flash('success', 'Cart updated successfully');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Item not found in cart.'
    ]);
}

    // REMOVE FROM CART
    public function removeCart(Request $request)
    {
        $itemId = $request->input('id');
        $cart = Session::get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);
            Session::flash('success', 'Item removed from cart');

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    // CLEAR CART
    public function clearCart()
    {
        Session::forget('cart');
        Session::flash('success', 'Cart cleared successfully');
        return redirect()->route('cart')->with('success', 'Cart cleared successfully');
    }

    // CHECKOUT
    public function checkout()
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty. Please add items to checkout.');
        }

        $tableNumber = Session::get('tableNumber');

        return view('customer.checkout', compact('cart'));
    }

    // STORE ORDER
    public function storeOrder(Request $request)
    {
        $cart = Session::get('cart');
        $tableNumber = Session::get('tableNumber');

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty. Please add items to checkout.');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')->withErrors($validator);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];

            $itemDetails = [
                'id' => $item['id'],
                'price' => (int) $item['price'] + ($item['price'] * 0.1),
                'quantity' => $item['qty'],
                'name' => substr($item['name'], 0, 50),
            ];
        }

        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname'),
            'phone' => $request->input('phone'),
            'role_id' => 4,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-'.$tableNumber.'-'. time(),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => $totalAmount * 0.1,
            'grand_total' => $totalAmount + ($totalAmount * 0.1),
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,

        ]);

        foreach ($cart as $itemId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'] * $item['qty'],
                'tax' => $item['price'] * $item['qty'] * 0.1,
                'total_price' => $item['price'] * $item['qty'] + ($item['price'] * $item['qty'] * 0.1),
            ]);
        }

        Session::forget('cart');

        return redirect()->route('checkout.success', ['orderId' => $order->order_code])->with('success', 'Order placed successfully!');
    }

    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code', $orderId)->first();
        if (!$order) {
            return redirect()->route('menu')->with('error', 'Order not found.');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        if ($order->payment_method === 'qris') {
            $order->status = 'settlement';
            $order->save();
        }

        return view('customer.success', compact('order', 'orderItems'));
    }

}
