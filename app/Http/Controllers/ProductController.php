<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TripayService;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('product.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

    public function checkout(Product $product)
    {
        $tripay = new TripayService();
        $channels = $tripay->getPaymentChannels();

        return view('product.checkout', compact('product', 'channels'));
    }
}
