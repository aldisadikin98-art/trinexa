<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['topup', 'withdraw', 'purchase', 'autosave', 'reward', 'recycle']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->string('reference_id')->nullable(); // transaction_id or midtrans order_id
            $table->enum('status', ['success', 'pending', 'failed'])->default('success');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
