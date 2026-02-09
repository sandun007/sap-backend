<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('supplier_id', 32)
                ->primary()
                ->first();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->bigIncrements('supplier_id')->first();
        });
    }
};

