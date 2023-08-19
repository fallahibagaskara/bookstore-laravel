<?php

namespace App\Http\Controllers;

use App\Services\TripayService;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function show($reference)
    {
        $detail = $this->tripayService->detailTransaction($reference);

        return view('order.show', compact('detail'));
    }

    public function store(Request $request)

    {
        // Request order to Tripay
        $product = Product::find($request->product_id);
        $method = $request->method;

        $order = $this->tripayService->requestTransaction($product, $method);

        // Create a New Data in Order Model to Database
        Order::create(
            [
                'user_id' => auth()->user()->id,
                'product_id' => $product->id,
                'chat_id' => "",
                'message_id' => "",
                'name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'product_name' => $order->order_items[0]->name,
                'product_value' => "example value",
                'quantity' => $order->order_items[0]->quantity,
                'price' => $order->order_items[0]->price,
                'fee_merchant' => $order->fee_merchant,
                'fee_customer' => $order->fee_customer,
                'total_fee' => $order->total_fee,
                'total_price' => $order->amount,
                'amount_received' => $order->amount_received,
                'method' => $order->payment_method,
                'status' => $order->status,
                'reference' => $order->reference,
                'merchant_ref' => $order->merchant_ref,
                'checkout_url' => $order->checkout_url,
            ]
        );

        return redirect()->route('order.show', ['reference' => $order->reference]);
    }
}
