<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->integer('dashboard_logo_size')->default(80)->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->dropColumn('dashboard_logo_size');
        });
    }
};
