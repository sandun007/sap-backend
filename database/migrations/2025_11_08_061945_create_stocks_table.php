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
        Schema::create('stock', function (Blueprint $table) {
            $table->id('stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('branch_id');
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock_level')->default(0);
            $table->integer('maximum_stock_level')->default(0);
            $table->date('last_restocked_date')->nullable();
            $table->string('location_in_warehouse')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
