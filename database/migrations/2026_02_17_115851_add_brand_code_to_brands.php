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
            $table->string('brand_code', 20)    // adjust length if needed (e.g. 10, 15, 30)
            ->nullable()
                ->after('brand_id');     // places it right after category_name
            // → remove ->after() if you don't care about column order
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('brand_code');
        });
    }
};



