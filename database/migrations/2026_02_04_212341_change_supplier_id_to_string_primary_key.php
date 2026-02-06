<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('suppliers', function (Blueprint $table) {
            $table->bigInteger('supplier_id')->unsigned()->change();

            $table->dropPrimary(['supplier_id']);

            $table->dropColumn('supplier_id');

            $table->string('supplier_id', 32)
                ->primary()
                ->first();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropPrimary(['supplier_id']);
            $table->dropColumn('supplier_id');

            $table->id('supplier_id')->first();
        });
    }
};
