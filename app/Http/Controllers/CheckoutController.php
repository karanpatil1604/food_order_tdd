<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Services\CartService;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        $checkout_items = $cart->get();
        $total = $cart->total();
        return view('checkout', ['checkout_items' => $checkout_items, 'total' => $total]);
    }

    public function create(CartService $cart)
    {
        $checkout_items = $cart->get();
        $total = $cart->total();
        $order = Order::create([
            'total' => $total,
        ]);
        foreach ($checkout_items as $item) {
            $order->detail()->create([
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'cost' => $item['cost']
            ]);
        }
        return redirect('/summary');
    }
}
