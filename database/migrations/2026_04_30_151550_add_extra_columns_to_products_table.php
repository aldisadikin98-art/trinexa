<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('brand', ['naturea', 'karebla'])->default('naturea')->after('type');
            $table->string('category')->nullable()->after('brand'); // e.g., serum, toner, cleanser, tumbler
            $table->boolean('is_bundle')->default(false)->after('category');
            $table->decimal('bundle_discount', 5, 2)->default(0)->after('is_bundle'); // percent
            $table->integer('reward_points')->default(0)->after('bundle_discount'); // points earned per purchase
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'category', 'is_bundle', 'bundle_discount', 'reward_points']);
        });
    }
};
