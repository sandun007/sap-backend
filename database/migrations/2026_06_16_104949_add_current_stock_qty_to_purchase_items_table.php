<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->integer('current_stock_qty')->nullable()->after('quantity');
        });

        // Set existing records' current_stock_qty equal to their quantity
        DB::statement('UPDATE purchase_items SET current_stock_qty = quantity WHERE current_stock_qty IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('current_stock_qty');
        });
    }
};
