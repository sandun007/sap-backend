<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('flag');
            $table->string('address');
            $table->string('location');
            $table->string('email')->nullable();
            $table->string('phone_no_01');
            $table->string('phone_no_02')->nullable();
            $table->string('website')->nullable();
            $table->string('company_id');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
