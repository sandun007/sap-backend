<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add foreign keys after all tables are created
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('manager_id')->references('employee_id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['manager_id']);
        });
    }
};