<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('payment_method', ['wallet', 'midtrans'])->default('wallet')->after('status');
            $table->string('midtrans_snap_token')->nullable()->after('payment_method');
            $table->string('midtrans_order_id')->nullable()->after('midtrans_snap_token');
            $table->text('shipping_address')->nullable()->after('midtrans_order_id');
            $table->string('courier')->nullable()->after('shipping_address');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('courier');
            $table->string('tracking_number')->nullable()->after('shipping_cost');
            $table->enum('shipping_status', ['pending', 'processed', 'shipped', 'delivered'])->default('pending')->after('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'midtrans_snap_token', 'midtrans_order_id',
                'shipping_address', 'courier', 'shipping_cost',
                'tracking_number', 'shipping_status'
            ]);
        });
    }
};
