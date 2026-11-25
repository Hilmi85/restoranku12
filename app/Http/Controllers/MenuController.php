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
                'message' => 'Menu tidak ditemukan.'
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
            'message' => 'Menu berhasil ditambahkan ke keranjang.',
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
            'message' => 'Jumlah menu tidak valid.'
        ]);
    }

    $cart = Session::get('cart');

    if (isset($cart[$itemId])) {
        $cart[$itemId]['qty'] = $newQty;
        Session::put('cart', $cart);
        Session::flash('success', 'Keranjang berhasil diperbarui.');

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui.'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Menu tidak ditemukan di keranjang.'
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
            Session::flash('success', 'Menu dihapus dari keranjang.');

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
        Session::flash('success', 'Keranjang berhasil dikosongkan.');
        return redirect()->route('cart')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    // CHECKOUT
    public function checkout()
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong. Tambahkan menu sebelum melanjutkan ke pembayaran.');
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
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong. Tambahkan menu sebelum melanjutkan ke pembayaran.');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')->withErrors($validator)->withInput();
        }

        $totalAmount = 0;
        $itemDetails = [];

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];

            $itemDetails[] = [
                'id' => (string) $item['id'],
                'price' => (int) $item['price'],
                'quantity' => (int) $item['qty'],
                'name' => substr($item['name'], 0, 50),
            ];
        }

        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname'),
            'phone' => $request->input('phone'),
        ], [
            'role_id' => 4,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-' . $tableNumber . '-' . time(),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => $totalAmount * 0.1,
            'grand_total' => $totalAmount + ($totalAmount * 0.1),
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
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

        if ($request->payment_method === 'tunai') {
            Session::forget('cart');

            return redirect()->route('checkout.success', ['orderId' => $order->order_code])->with('success', 'Pesanan berhasil dibuat.');
        } else {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $user->fullname ?? 'Guest',
                    'phone' => $user->phone,
                ],
                'enabled_payments' => ['gopay'],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                Session::forget('cart');

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_code' => $order->order_code,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage(),
                ], 500);
            }
        }
    }
    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan.');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        return view('customer.success', compact('order', 'orderItems'));
    }

}
