<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with('images')
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('title')
            ->get();

        return view('products', compact('products'));
    }
}
