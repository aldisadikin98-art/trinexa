<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. TRINEXA10
            $table->string('name');
            $table->enum('type', ['percent', 'nominal']); // percent = %, nominal = Rp
            $table->decimal('value', 10, 2); // e.g. 10 (%) or 15000 (Rp)
            $table->decimal('min_purchase', 15, 2)->default(0);
            $table->decimal('max_discount', 15, 2)->nullable(); // cap untuk tipe percent
            $table->timestamp('expired_at')->nullable();
            $table->integer('quota')->nullable(); // null = unlimited
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_vouchers');
    }
};
