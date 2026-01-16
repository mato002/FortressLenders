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

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('images');
        
        // Get related products
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->orderBy('display_order')
            ->limit(3)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
