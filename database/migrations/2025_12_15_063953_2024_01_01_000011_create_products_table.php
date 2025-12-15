<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->decimal('price', 10, 2);
            $table->string('unit');
            $table->text('status')->nullable();
            $table->text('description')->nullable();

            $table->foreign('brand_id')->references('brand_id')->on('brands')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('subcategory_id')->on('sub_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};