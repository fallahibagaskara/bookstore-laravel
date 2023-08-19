<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('chat_id')->nullable();
            $table->string('message_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('product_name');
            $table->string('product_value');
            $table->integer('quantity');
            $table->string('price');
            $table->string('method');
            $table->string('fee_merchant');
            $table->string('fee_customer');
            $table->string('total_fee');
            $table->string('total_price');
            $table->string('amount_received');
            $table->enum('status', ['paid', 'unpaid', 'expired']);
            $table->string('reference');
            $table->string('merchant_ref');
            $table->string('checkout_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
