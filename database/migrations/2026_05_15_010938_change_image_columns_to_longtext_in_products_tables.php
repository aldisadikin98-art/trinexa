<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Change image_url to longText on products and karebla_products
     * so that base64-encoded images can be stored directly in the DB
     * (bypassing Railway's ephemeral filesystem).
     */
    public function up(): void
    {
        // products.image_url was string(255) — too small for base64
        Schema::table('products', function (Blueprint $table) {
            $table->longText('image_url')->nullable()->change();
        });

        // karebla_products has no image_url but images is json —
        // JSON in MySQL can hold up to 1 GB so no change needed there.
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_url')->nullable()->change();
        });
    }
};
