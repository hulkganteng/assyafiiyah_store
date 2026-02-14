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
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique();
            $table->string('status')->default('pending_payment');
            $table->decimal('total_price', 12, 2);
            $table->string('shipping_method');
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->text('shipping_address')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('payment_method');
            $table->text('notes')->nullable();
            $table->timestamps();
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
