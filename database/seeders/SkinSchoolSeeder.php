<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkinCategory;
use App\Models\SkinContent;

class SkinSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Skin Basics', 'slug' => 'skin-basics', 'order' => 1],
            ['name' => 'Ingredients', 'slug' => 'ingredients', 'order' => 2],
            ['name' => 'Routine Build', 'slug' => 'routine-build', 'order' => 3],
            ['name' => 'Skin Expert', 'slug' => 'skin-expert', 'order' => 4],
        ];

        foreach ($categories as $cat) {
            SkinCategory::create($cat);
        }

        $basicsId = SkinCategory::where('slug', 'skin-basics')->first()->id;
        $ingredientsId = SkinCategory::where('slug', 'ingredients')->first()->id;
        $routineId = SkinCategory::where('slug', 'routine-build')->first()->id;

        // Articles (5)
        SkinContent::create([
            'category_id' => $basicsId,
            'title' => 'Urutan Skincare yang Benar: Panduan Lengkap untuk Pemula',
            'slug' => 'urutan-skincare-yang-benar',
            'type' => 'article',
            'skin_type' => 'all',
            'content' => 'Lorem ipsum dolor sit amet. Ini adalah artikel panduan pemula tentang urutan skincare dari cleanser sampai sunscreen.',
            'thumbnail' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop',
            'duration' => 5,
            'views' => 2400,
            'is_featured' => true,
        ]);

        SkinContent::create([
            'category_id' => $ingredientsId,
            'title' => 'Mengenal Niacinamide: Si Bahan Ajaib untuk Kulit Cerah',
            'slug' => 'mengenal-niacinamide',
            'type' => 'article',
            'skin_type' => 'all',
            'content' => 'Niacinamide sangat bagus untuk mencerahkan kulit dan mengontrol sebum.',
            'thumbnail' => 'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?q=80&w=600&auto=format&fit=crop',
            'duration' => 4,
            'views' => 1200,
        ]);

        SkinContent::create([
            'category_id' => $basicsId,
            'title' => 'Kenapa Sunscreen Wajib Dipakai Setiap Hari, Bahkan di Dalam Ruangan?',
            'slug' => 'sunscreen-setiap-hari',
            'type' => 'article',
            'skin_type' => 'all',
            'content' => 'UV A dapat menembus kaca, oleh karena itu sunscreen wajib dipakai.',
            'thumbnail' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?q=80&w=600&auto=format&fit=crop',
            'duration' => 3,
            'views' => 3100,
        ]);

        SkinContent::create([
            'category_id' => $ingredientsId,
            'title' => 'BHA vs AHA: Mana yang Cocok Untukmu?',
            'slug' => 'bha-vs-aha',
            'type' => 'article',
            'skin_type' => 'oily',
            'content' => 'AHA untuk permukaan kulit, BHA bisa masuk ke pori-pori.',
            'thumbnail' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?q=80&w=600&auto=format&fit=crop',
            'duration' => 6,
            'views' => 800,
        ]);

        SkinContent::create([
            'category_id' => $routineId,
            'title' => 'Cara Mengatasi Kulit Kering Saat Berpuasa',
            'slug' => 'mengatasi-kulit-kering-puasa',
            'type' => 'article',
            'skin_type' => 'dry',
            'content' => 'Perbanyak minum air saat sahur dan gunakan moisturizer berbahan ceramide.',
            'thumbnail' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop',
            'duration' => 4,
            'views' => 950,
        ]);

        // Tips (4)
        SkinContent::create([
            'category_id' => $basicsId,
            'title' => 'Hidrasi dari dalam',
            'slug' => 'hidrasi-dari-dalam',
            'type' => 'tip',
            'skin_type' => 'all',
            'content' => 'Minum 8 gelas air per hari terbukti membantu kulit tetap kenyal dan mengurangi tampilan garis halus.',
            'is_weekly_tip' => true,
        ]);

        SkinContent::create([
            'category_id' => $routineId,
            'title' => 'Skincare malam itu penting',
            'slug' => 'skincare-malam-penting',
            'type' => 'tip',
            'skin_type' => 'all',
            'content' => 'Kulit beregenerasi saat tidur. Pakai serum atau moisturizer malam untuk hasil maksimal.',
            'is_weekly_tip' => true,
        ]);

        SkinContent::create([
            'category_id' => $ingredientsId,
            'title' => 'Pilih bahan alami',
            'slug' => 'pilih-bahan-alami',
            'type' => 'tip',
            'skin_type' => 'sensitive',
            'content' => 'Bahan seperti aloe vera, green tea extract, dan centella asiatica cocok untuk semua jenis kulit.',
            'is_weekly_tip' => true,
        ]);

        SkinContent::create([
            'category_id' => $basicsId,
            'title' => 'Patch test dulu',
            'slug' => 'patch-test-dulu',
            'type' => 'tip',
            'skin_type' => 'sensitive',
            'content' => 'Sebelum pakai produk baru, oleskan di area kecil kulit dan tunggu 24 jam untuk cek reaksi.',
            'is_weekly_tip' => true,
        ]);

        // Videos (3)
        SkinContent::create([
            'category_id' => $routineId,
            'title' => 'Morning Routine 5 Langkah dengan Produk Naturea',
            'slug' => 'morning-routine-naturea',
            'type' => 'video',
            'skin_type' => 'all',
            'content' => 'Video tutorial morning routine menggunakan Naturea.',
            'thumbnail' => 'https://images.unsplash.com/photo-1576426863848-c21f53c60b19?q=80&w=600&auto=format&fit=crop',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => 8,
            'views' => 1800,
            'is_featured' => true,
        ]);

        SkinContent::create([
            'category_id' => $basicsId,
            'title' => 'Double Cleansing: Cara Cuci Muka yang Benar di Malam Hari',
            'slug' => 'double-cleansing-benar',
            'type' => 'video',
            'skin_type' => 'all',
            'content' => 'Pentingnya double cleansing untuk mengangkat sisa makeup dan sunscreen.',
            'thumbnail' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?q=80&w=600&auto=format&fit=crop',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => 6,
            'views' => 3000,
        ]);

        SkinContent::create([
            'category_id' => $ingredientsId,
            'title' => 'Tips Memilih Serum Sesuai Masalah Kulit',
            'slug' => 'memilih-serum-sesuai',
            'type' => 'video',
            'skin_type' => 'combination',
            'content' => 'Panduan lengkap memilih serum yang tepat.',
            'thumbnail' => 'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?q=80&w=600&auto=format&fit=crop',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => 10,
            'views' => 1500,
        ]);
    }
}
