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
        Schema::create('skin_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('skin_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['article', 'tip', 'video']);
            $table->enum('skin_type', ['all', 'oily', 'dry', 'combination', 'sensitive'])->default('all');
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('duration')->default(0);
            $table->integer('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_weekly_tip')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skin_contents');
    }
};
