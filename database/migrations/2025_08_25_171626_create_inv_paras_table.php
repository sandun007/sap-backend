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
        Schema::create('inv_paras', function (Blueprint $table) {
            $table->id();
            $table->string('company_flag');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('email')->nullable();
            $table->string('phone_no_01');
            $table->string('phone_no_02')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->integer('product_code')->default(1);
            $table->integer('brand_code')->default(1);
            $table->integer('category_code')->default(1);
            $table->integer('invoice_no')->default(1);
            $table->integer('grn_no')->default(1);
            $table->integer('branch_code')->default(1);
            $table->integer('bank_code')->default(1);
            $table->integer('customer_code')->default(1);
            $table->integer('employee_code')->default(1);
            $table->integer('supplier_code')->default(1);
            $table->integer('department_code')->default(1);
            $table->integer('expenses_code')->default(1);
            $table->integer('store_code')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_paras');
    }
};
