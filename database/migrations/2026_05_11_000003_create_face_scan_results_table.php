<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('photo_path');
            $table->string('skin_type');
            $table->integer('skin_score');
            $table->string('score_label');
            $table->json('conditions');
            $table->json('good_ingredients');
            $table->json('bad_ingredients');
            $table->json('morning_routine');
            $table->json('night_routine');
            $table->json('tips');
            $table->text('summary');
            $table->json('recommended_product_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_scan_results');
    }
};
