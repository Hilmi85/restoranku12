<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Item;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');
        if ($tableNumber) {
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('customer.menu', compact('items', 'tableNumber'));
    }

    public function cart() {
        $cart = Session::get('cart');
        return view('customer.cart', compact('cart'));
    }

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

    public function clearCart()
    {
        Session::forget('cart');
        Session::flash('success', 'Cart cleared successfully');
        return redirect()->route('cart')->with('success', 'Cart cleared successfully');
    }


}
