<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchProductsController extends Controller
{
    //
    public function index()
    {
        $query_str = request('query');
        // -----------------------------Initial implementation ----------------
        // $items = Product::when($query_str, function ($query, $query_str) {
        //     return $query->where('name', 'LIKE', "%{$query_str}%");
        // })->get(); 

        // ----------------------------- Refactoring -----------------------------
        $items = Product::mathces($query_str)->get(); // use mathces method from Product
        return view('search', compact('items', 'query_str'));
    }
}
