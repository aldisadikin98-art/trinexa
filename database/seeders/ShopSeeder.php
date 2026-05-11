<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ShopVoucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Shop Vouchers
        $vouchers = [
            [
                'code'         => 'NATUREA50',
                'name'         => 'Diskon Launching 50%',
                'type'         => 'percent',
                'value'        => 50,
                'min_purchase' => 100000,
                'max_discount' => 50000,
                'quota'        => 100,
                'is_active'    => true,
            ],
            [
                'code'         => 'HEMAT20K',
                'name'         => 'Potongan Langsung 20Rb',
                'type'         => 'nominal',
                'value'        => 20000,
                'min_purchase' => 150000,
                'max_discount' => null,
                'quota'        => null, // unlimited
                'is_active'    => true,
            ]
        ];

        foreach ($vouchers as $v) {
            ShopVoucher::firstOrCreate(['code' => $v['code']], $v);
        }

        // 2. Seed Naturea Products (Skincare Alami)
        $products = [
            [
                'name'        => 'Naturea C-Glow Serum 20ml',
                'description' => 'Serum Vitamin C dengan ekstrak Kakadu Plum organik. Mencerahkan kulit kusam, memudarkan bekas jerawat, dan meratakan warna kulit dalam 14 hari pemakaian rutin.',
                'price'       => 129000,
                'stock'       => 50,
                'category'    => 'Serum',
                'image_url'   => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&q=80',
                'ingredients' => ['Vitamin C (Ascorbic Acid) 10%', 'Kakadu Plum Extract', 'Hyaluronic Acid'],
                'skin_type'   => ['Normal', 'Kering', 'Kombinasi'],
                'benefits'    => 'Mencerahkan dan melembapkan kulit. Mengurangi noda hitam.',
                'bpom_number' => 'NA18230101234',
            ],
            [
                'name'        => 'Naturea Centella Calming Toner 100ml',
                'description' => 'Toner eksfoliasi lembut sekaligus menenangkan. Diformulasikan dengan ekstrak Centella Asiatica murni untuk meredakan kemerahan dan menyeimbangkan pH kulit.',
                'price'       => 89000,
                'stock'       => 100,
                'category'    => 'Toner',
                'image_url'   => 'https://images.unsplash.com/photo-1608248593842-8d76b1f22e7d?w=600&q=80',
                'ingredients' => ['Centella Asiatica 80%', 'Panthenol', 'Allantoin'],
                'skin_type'   => ['Sensitif', 'Berjerawat', 'Kombinasi'],
                'benefits'    => 'Menenangkan kulit kemerahan, menghidrasi, dan memperkuat skin barrier.',
                'bpom_number' => 'NA18231201555',
            ],
            [
                'name'        => 'Naturea Oat Ceramide Moisturizer 50g',
                'description' => 'Pelembap bertekstur gel-krim ringan yang mengunci kelembapan hingga 24 jam. Kombinasi Colloidal Oatmeal dan 5 jenis Ceramide memperbaiki skin barrier yang rusak.',
                'price'       => 145000,
                'stock'       => 3, // Low stock simulation
                'category'    => 'Moisturizer',
                'image_url'   => 'https://images.unsplash.com/photo-1611077543202-b258ca861e6c?w=600&q=80',
                'ingredients' => ['Colloidal Oatmeal', '5X Ceramide', 'Shea Butter'],
                'skin_type'   => ['Kering', 'Sensitif', 'Normal'],
                'benefits'    => 'Memperbaiki skin barrier, melembapkan kulit kering kerontang.',
                'bpom_number' => 'NA18230108888',
            ],
            [
                'name'        => 'Naturea Matcha Gentle Cleanser 100ml',
                'description' => 'Pembersih wajah pH seimbang dengan ekstrak teh hijau antioksidan tinggi. Membersihkan kotoran tanpa membuat kulit terasa kering atau ketarik.',
                'price'       => 75000,
                'stock'       => 75,
                'category'    => 'Cleanser',
                'image_url'   => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80',
                'ingredients' => ['Matcha Extract', 'Salicylic Acid 0.5%', 'Glycerin'],
                'skin_type'   => ['Berminyak', 'Berjerawat', 'Kombinasi'],
                'benefits'    => 'Membersihkan pori-pori secara mendalam, mengontrol sebum berlebih.',
                'bpom_number' => 'NA18231209999',
            ],
            [
                'name'        => 'Naturea UV Shield Watery Sunscreen SPF 50',
                'description' => 'Tabir surya dengan tekstur watery yang sangat ringan, tanpa whitecast, dan cepat meresap. Melindungi kulit dari UVA dan UVB serta polusi.',
                'price'       => 110000,
                'stock'       => 0, // Out of stock simulation
                'category'    => 'Sunscreen',
                'image_url'   => 'https://images.unsplash.com/photo-1556228720-192b61ccb8dc?w=600&q=80',
                'ingredients' => ['Chemical UV Filters', 'Niacinamide 2%', 'Aloe Vera'],
                'skin_type'   => ['Semua Jenis Kulit'],
                'benefits'    => 'Melindungi dari sinar matahari, tidak lengket, cocok di bawah makeup.',
                'bpom_number' => 'NA18231700123',
            ],
            [
                'name'        => 'Naturea Bakuchiol Night Treatment 30ml',
                'description' => 'Alternatif retinol alami yang aman untuk kulit sensitif dan ibu hamil. Menyamarkan garis halus dan menstimulasi produksi kolagen saat tidur.',
                'price'       => 155000,
                'stock'       => 20,
                'category'    => 'Treatment',
                'image_url'   => 'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=600&q=80',
                'ingredients' => ['Bakuchiol 2%', 'Squalane', 'Peptide'],
                'skin_type'   => ['Semua Jenis Kulit', 'Sensitif'],
                'skin_type_not_suitable' => 'Hindari penggunaan pada kulit yang sedang breakout parah.',
                'benefits'    => 'Anti-aging alami, menghaluskan tekstur kulit.',
                'bpom_number' => 'NA18232000456',
            ]
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['slug' => Str::slug($p['name'])],
                array_merge($p, [
                    'type'          => 'skincare',
                    'brand'         => 'naturea',
                    'images'        => [$p['image_url']],
                    'is_active'     => true,
                    'reward_points' => 0, // Points are now calculated dynamically (1 coin per 10k) in system
                ])
            );
        }
    }
}
