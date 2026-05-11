<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_item_id')->constrained('transaction_items')->cascadeOnDelete();
            $table->tinyInteger('rating'); // 1-5
            $table->string('skin_type')->nullable(); // jenis kulit reviewer
            $table->text('body'); // teks ulasan, min 20 char
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_verified_purchase')->default(true);
            $table->integer('helpful_count')->default(0);
            $table->text('admin_reply')->nullable();
            $table->timestamp('admin_replied_at')->nullable();
            $table->timestamps();

            // 1 user = 1 ulasan per produk per transaksi
            $table->unique(['user_id', 'product_id', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
