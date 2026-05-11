<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skin_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->string('result_type')->nullable(); // oily, dry, combination, normal
            $table->json('result_scores')->nullable(); // {oily: 40, dry: 30, acne: 20}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skin_questionnaires');
    }
};
