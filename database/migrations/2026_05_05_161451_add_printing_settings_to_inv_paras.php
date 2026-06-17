<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->boolean('auto_print_grn')->default(false)->after('logo');
            $table->boolean('show_print_preview')->default(true)->after('auto_print_grn');
            $table->string('print_footer_text')->nullable()->after('show_print_preview');
        });
    }

    public function down(): void
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->dropColumn(['auto_print_grn', 'show_print_preview', 'print_footer_text']);
        });
    }
};
