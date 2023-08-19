<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'chat_id', 'message_id', 'name', 'email', 'phone', 'product_name', 'product_value', 'quantity', 'price', 'fee_merchant', 'fee_customer', 'total_fee', 'total_price', 'amount_received', 'method', 'status', 'reference', 'merchant_ref', 'checkout_url'];

    public function user()
    {
        return $this->belongsTo((User::class));
    }

    public function product()
    {
        return $this->belongsTo((Product::class));
    }
}
