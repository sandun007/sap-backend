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
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_code')->nullable()->after('product_id')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->after('description');
            $table->string('barcode')->nullable()->after('user_id');
            $table->decimal('cost_price', 10, 2)->nullable()->after('barcode');
            $table->string('weight')->nullable()->after('cost_price');
            $table->string('dimensions')->nullable()->after('weight');
            $table->string('supplier_id', 32)->nullable()->after('dimensions');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Check if foreign keys exist before dropping
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $keys = $sm->listTableForeignKeys('products');
            $keyNames = array_map(fn($k) => $k->getName(), $keys);

            if (in_array('products_user_id_foreign', $keyNames)) {
                $table->dropForeign(['user_id']);
            }
            if (in_array('products_supplier_id_foreign', $keyNames)) {
                $table->dropForeign(['supplier_id']);
            }
            
            // Check if columns exist before dropping
            $cols = ['product_code', 'user_id', 'barcode', 'cost_price', 'weight', 'dimensions', 'supplier_id'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
