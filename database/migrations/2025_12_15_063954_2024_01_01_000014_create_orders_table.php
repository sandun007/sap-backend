<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_number')->unique();
            $table->date('order_date')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->float('total_amount')->default(0);
            $table->string('status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('set null');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};