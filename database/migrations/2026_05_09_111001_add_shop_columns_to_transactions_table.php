<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update status enum to include full order lifecycle
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending','paid','failed','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending'");

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('receipt_number')->unique()->nullable()->after('id');
            $table->unsignedBigInteger('shop_voucher_id')->nullable()->after('shipping_status');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('shop_voucher_id');
            $table->integer('coins_earned')->default(0)->after('discount_amount');
            $table->enum('coins_status', ['pending', 'credited'])->default('pending')->after('coins_earned');
            $table->timestamp('cancelled_at')->nullable()->after('coins_status');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');

            $table->foreign('shop_voucher_id')->references('id')->on('shop_vouchers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['shop_voucher_id']);
            $table->dropColumn([
                'receipt_number', 'shop_voucher_id', 'discount_amount',
                'coins_earned', 'coins_status', 'cancelled_at', 'cancellation_reason',
            ]);
        });

        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending','paid','failed') DEFAULT 'pending'");
    }
};
