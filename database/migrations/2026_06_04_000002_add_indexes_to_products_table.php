<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan composite index pada products(brand, is_active, created_at)
     * untuk mengatasi "Out of sort memory" saat ORDER BY pada tabel dengan kolom LONGTEXT.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Index untuk query: WHERE is_active = 1 AND brand = ? ORDER BY created_at DESC
            $table->index(['brand', 'is_active', 'created_at'], 'products_brand_active_created_index');

            // Index tambahan untuk sort by price
            $table->index(['brand', 'is_active', 'price'], 'products_brand_active_price_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_brand_active_created_index');
            $table->dropIndex('products_brand_active_price_index');
        });
    }
};
