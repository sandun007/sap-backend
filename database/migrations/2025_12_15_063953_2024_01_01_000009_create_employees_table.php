<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('position')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('department_id'); // Will add FK later
            $table->unsignedBigInteger('manager_id')->nullable(); // Will add FK later
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // NOTE: Foreign keys will be added in separate migration
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};