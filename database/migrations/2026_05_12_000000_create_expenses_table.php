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
        Schema::create('expenses', function (Blueprint $row) {
            $row->id();
            $row->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $row->date('date');
            $row->string('category'); // stok, operasional, gaji, marketing, lain-lain
            $row->string('description');
            $row->decimal('amount', 15, 2);
            $row->string('receipt_path')->nullable();
            $row->softDeletes();
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
