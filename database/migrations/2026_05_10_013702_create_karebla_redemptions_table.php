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
        Schema::create('karebla_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('karebla_product_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->integer('coins_used');
            $table->text('shipping_address');
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'selesai'])->default('menunggu');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karebla_redemptions');
    }
};
