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
        Schema::table('employees', function (Blueprint $table) {
            // Rename phone to phone_num1
            if (Schema::hasColumn('employees', 'phone') && !Schema::hasColumn('employees', 'phone_num1')) {
                DB::statement("ALTER TABLE employees CHANGE phone phone_num1 VARCHAR(255) NULL");
            }

            // Add phone_num2
            if (!Schema::hasColumn('employees', 'phone_num2')) {
                $table->string('phone_num2')->nullable();
            }

            // Add nic_num
            if (!Schema::hasColumn('employees', 'nic_num')) {
                $table->string('nic_num')->nullable();
            }

            // Add nic_front
            if (!Schema::hasColumn('employees', 'nic_front')) {
                $table->string('nic_front')->nullable(); // nic front img
            }

            // Add nic_back
            if (!Schema::hasColumn('employees', 'nic_back')) {
                $table->string('nic_back')->nullable(); // nic back img
            }


            // Drop manager_id
            if (Schema::hasColumn('employees', 'manager_id')) {
                $table->dropColumn('manager_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Reverse: rename phone_num1 back to phone
            if (Schema::hasColumn('employees', 'phone_num1') && !Schema::hasColumn('employees', 'phone')) {
                $table->renameColumn('phone_num1', 'phone');
            }

            // Reverse: drop newly added columns
            $table->dropColumn(array_filter(
                ['phone_num2', 'nic_num', 'nic_front', 'nic_back'],
                fn($col) => Schema::hasColumn('employees', $col)
            ));

            // Reverse: re-add manager_id column
            if (!Schema::hasColumn('employees', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('department_id');
            }
        });
    }
};
