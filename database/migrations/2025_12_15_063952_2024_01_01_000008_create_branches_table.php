<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->string('branch_name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->unsignedBigInteger('manager_id')->nullable(); // Will add FK later
            $table->date('opening_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // NOTE: Foreign key for manager_id will be added in separate migration
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};