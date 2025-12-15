<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('department_name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable(); // Will add FK later
            $table->unsignedBigInteger('branch_id');
            $table->date('created_date')->nullable();
            $table->timestamps();

            // Add branch foreign key (branches table exists already)
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            
            // NOTE: Foreign key for manager_id will be added in separate migration
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};