<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Models\Voucher;

class LoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Badges
        $badges = [
            [
                'name' => 'First Buy',
                'description' => 'Selesaikan transaksi pertama Anda',
                'requirement_type' => 'shop',
                'requirement_value' => 1,
            ],
            [
                'name' => 'Skin Learner',
                'description' => 'Selesaikan 5 artikel Skin School',
                'requirement_type' => 'article',
                'requirement_value' => 5,
            ],
            [
                'name' => 'Streak 7',
                'description' => 'Login 7 hari berturut-turut',
                'requirement_type' => 'streak',
                'requirement_value' => 7,
            ],
            [
                'name' => 'Gold Member',
                'description' => 'Capai level Gold Member',
                'requirement_type' => 'level',
                'requirement_value' => 2, // Gold map is 2
            ],
            [
                'name' => 'Eco Warrior',
                'description' => 'Recycle 10 botol kosong',
                'requirement_type' => 'recycle',
                'requirement_value' => 10,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['name' => $badge['name']], $badge);
        }

        // 2. Seed Vouchers
        $vouchers = [
            [
                'name' => 'Diskon 5% Semua Produk',
                'description' => 'Gunakan untuk mendapatkan diskon 5%',
                'points_required' => 500,
                'discount_amount' => 5,
                'discount_type' => 'percent',
                'min_level' => 'Silver',
                'max_uses' => null,
            ],
            [
                'name' => 'Free Ongkir 2x',
                'description' => 'Gratis ongkir hingga Rp20.000 (Maks 2x/Bulan)',
                'points_required' => 800,
                'discount_amount' => 20000,
                'discount_type' => 'fixed',
                'min_level' => 'Silver',
                'max_uses' => null,
            ],
            [
                'name' => 'Diskon 10% Eksklusif',
                'description' => 'Hanya untuk member Gold ke atas',
                'points_required' => 1500,
                'discount_amount' => 10,
                'discount_type' => 'percent',
                'min_level' => 'Gold',
                'max_uses' => null,
            ],
            [
                'name' => 'Diskon 15% VIP',
                'description' => 'Potongan 15% untuk transaksi apapun',
                'points_required' => 3000,
                'discount_amount' => 15,
                'discount_type' => 'percent',
                'min_level' => 'Platinum',
                'max_uses' => null,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(['name' => $voucher['name']], $voucher);
        }
    }
}
