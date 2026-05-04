<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_id')->unique();
            $table->string('supplier_id', 32);
            $table->unsignedBigInteger('branch_id');
            $table->date('purchase_date');
            $table->string('reference_no')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->string('payment_method')->default('Cash');
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->text('payment_notes')->nullable();
            $table->text('notes')->nullable();
            
            // Payment details
            $table->string('bank_name')->nullable();
            $table->string('bank_reference')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_last_four')->nullable();
            $table->decimal('loan_interest_rate', 5, 2)->nullable();
            $table->integer('loan_term_months')->nullable();
            
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('cascade');
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_record_id'); // Internal ID
            $table->string('purchase_id'); // Public GRN ID
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->decimal('stock_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            $table->foreign('purchase_record_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};
