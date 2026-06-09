<?php
// database/migrations/xxxx_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->string('invoice_number')->unique();
            $table->decimal('net_total', 10, 2);
            $table->enum('payment_method', ['cash', 'card']);
            $table->decimal('cash_given', 10, 2)->nullable();
            $table->decimal('balance_returned', 10, 2)->nullable();
            $table->enum('status', ['paid', 'unpaid'])->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};