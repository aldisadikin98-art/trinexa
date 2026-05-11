<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Naturea Skincare
            [
                'name' => 'Naturea Glowing Serum',
                'description' => 'Serum wajah dengan ekstrak bahan alami untuk mencerahkan dan menyamarkan noda hitam. Cocok untuk semua jenis kulit.',
                'price' => 149000,
                'stock' => 50,
                'type' => 'skincare',
                'image_url' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Naturea',
                'category' => 'Serum',
                'is_bundle' => false,
                'bundle_discount' => 0,
                'reward_points' => 150,
            ],
            [
                'name' => 'Naturea Hydrating Toner',
                'description' => 'Toner menyegarkan yang mengembalikan pH alami kulit dan memberikan hidrasi mendalam sepanjang hari.',
                'price' => 99000,
                'stock' => 100,
                'type' => 'skincare',
                'image_url' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Naturea',
                'category' => 'Toner',
                'is_bundle' => false,
                'bundle_discount' => 0,
                'reward_points' => 100,
            ],
            [
                'name' => 'Naturea Daily Moisturizer',
                'description' => 'Pelembap ringan yang tidak lengket. Mengunci kelembapan kulit hingga 24 jam dengan kandungan ceramide alami.',
                'price' => 125000,
                'stock' => 75,
                'type' => 'skincare',
                'image_url' => 'https://images.unsplash.com/photo-1611078489935-0cb964de46d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Naturea',
                'category' => 'Moisturizer',
                'is_bundle' => false,
                'bundle_discount' => 0,
                'reward_points' => 125,
            ],
            [
                'name' => 'Naturea Complete Skincare Set',
                'description' => 'Paket hemat berisi Serum, Toner, dan Moisturizer. Dapatkan kulit sehat bercahaya dalam 14 hari.',
                'price' => 373000,
                'stock' => 20,
                'type' => 'skincare',
                'image_url' => 'https://images.unsplash.com/photo-1571781926291-c477eb3af7dd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Naturea',
                'category' => 'Bundle',
                'is_bundle' => true,
                'bundle_discount' => 15, // 15% discount
                'reward_points' => 500,
            ],

            // Karebla Eco-Lifestyle
            [
                'name' => 'Karebla Bamboo Tumbler',
                'description' => 'Tumbler premium dengan lapisan bambu asli. Mampu menahan panas dan dingin hingga 12 jam. Kurangi sampah plastik mulai sekarang!',
                'price' => 185000,
                'stock' => 30,
                'type' => 'tumbler',
                'image_url' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Karebla',
                'category' => 'Tumbler',
                'is_bundle' => false,
                'bundle_discount' => 0,
                'reward_points' => 185,
            ],
            [
                'name' => 'Karebla Eco Tote Bag',
                'description' => 'Tas belanja berbahan kanvas tebal ramah lingkungan dengan desain minimalis elegan. Sangat kuat untuk membawa banyak barang belanjaan.',
                'price' => 75000,
                'stock' => 200,
                'type' => 'tumbler',
                'image_url' => 'https://images.unsplash.com/photo-1597484661643-2f5fef640df1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Karebla',
                'category' => 'Bag',
                'is_bundle' => false,
                'bundle_discount' => 0,
                'reward_points' => 75,
            ],
            [
                'name' => 'Karebla Reusable Straw Set',
                'description' => 'Satu set sedotan stainless steel (lurus, bengkok, bubble tea) lengkap dengan sikat pembersih dan pouch cantik.',
                'price' => 45000,
                'stock' => 150,
                'type' => 'tumbler',
                'image_url' => 'https://images.unsplash.com/photo-1585834850383-bd087bb8c5c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'brand' => 'Karebla',
                'category' => 'Accessories',
                'is_bundle' => false,
                'bundle_discount' => 0,
                'reward_points' => 45,
            ]
        ];

        foreach ($products as $product) {
            $product['slug'] = \Illuminate\Support\Str::slug($product['name']);
            Product::create($product);
        }
    }
}
