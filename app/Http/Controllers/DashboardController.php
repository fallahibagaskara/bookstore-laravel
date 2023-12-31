<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'product'])->latest()->get();
        return view('dashboard', compact('orders'));
    }
}
