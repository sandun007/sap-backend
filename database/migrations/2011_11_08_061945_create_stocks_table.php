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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->string('product_name');
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock_level')->default(10);
            $table->integer('maximum_stock_level')->default(100);
            $table->date('last_restocked_date')->nullable();
            $table->string('location_in_warehouse')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            $table->foreign('brand_id')->references('brand_id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('subcategory_id')->on('sub_categories')->onDelete('cascade');
            
            // Unique constraint
            $table->unique(['product_id', 'branch_id']);
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