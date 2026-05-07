<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            if (!Schema::hasColumn('inv_paras', 'grn_print_size')) {
                $table->string('grn_print_size')->default('A4')->after('print_footer_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->dropColumn('grn_print_size');
        });
    }
};
