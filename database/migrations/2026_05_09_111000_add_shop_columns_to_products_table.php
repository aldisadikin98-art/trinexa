<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
            $table->json('images')->nullable()->after('image_url'); // array of image URLs
            $table->json('ingredients')->nullable()->after('images'); // kandungan utama, e.g. ["Niacinamide 10%", "Hyaluronic Acid"]
            $table->json('skin_type')->nullable()->after('ingredients'); // ["kering", "berminyak", "kombinasi", "sensitif", "normal"]
            $table->text('skin_type_not_suitable')->nullable()->after('skin_type');
            $table->text('usage_instructions')->nullable()->after('skin_type_not_suitable');
            $table->text('benefits')->nullable()->after('usage_instructions');
            $table->string('bpom_number')->nullable()->after('benefits');
            $table->boolean('is_active')->default(true)->after('bpom_number');
            $table->integer('coin_price')->default(0)->after('is_active'); // untuk Karebla — harga dalam koin
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'images', 'ingredients', 'skin_type', 'skin_type_not_suitable',
                'usage_instructions', 'benefits', 'bpom_number', 'is_active', 'coin_price',
            ]);
        });
    }
};
