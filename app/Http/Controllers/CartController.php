<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use SebastianBergmann\Complexity\Complexity;

class CartController extends Controller
{
    public function index()
    {
        $items = Product::get();
        return view('cart', compact('items'));
    }


    public function store()
    {
        $existing = collect(session('cart'))->first(fn ($row, $key) => $row['id'] === request('id'));
        if (!$existing) {
            session()->push('cart', [
                'id' => request('id'),
                'qty' => 1,
            ]);
        }
        // dd(session());
        return redirect('/cart');
    }
}
