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
        Schema::table('user_skin_progress', function (Blueprint $table) {
            $table->integer('xp_earned')->default(0)->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_skin_progress', function (Blueprint $table) {
            $table->dropColumn('xp_earned');
        });
    }
};
