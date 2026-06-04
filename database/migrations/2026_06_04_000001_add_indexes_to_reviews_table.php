<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan composite index pada reviews(product_id, status)
     * untuk mengatasi "Out of sort memory" saat withCount + withAvg.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Composite index: mempercepat subquery WHERE product_id = ? AND status = 'approved'
            $table->index(['product_id', 'status'], 'reviews_product_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_product_status_index');
        });
    }
};
