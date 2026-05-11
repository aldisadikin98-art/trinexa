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
        Schema::table('skin_contents', function (Blueprint $table) {
            $table->integer('xp_reward')->default(30)->after('is_featured');
            $table->boolean('is_published')->default(true)->after('xp_reward');
            $table->integer('read_time')->default(5)->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skin_contents', function (Blueprint $table) {
            $table->dropColumn(['xp_reward', 'is_published', 'read_time']);
        });
    }
};
