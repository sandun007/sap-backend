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
        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->change();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->string('brand_id', 32)
                ->primary()
                ->first();
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->bigIncrements('brand_id')->first();
        });
    }
};
