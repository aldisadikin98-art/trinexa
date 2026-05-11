<?php

namespace Database\Seeders;

use App\Models\SkinContent;
use App\Models\SkinCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DermatologySeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada kategori default
        $category = SkinCategory::firstOrCreate(
            ['slug' => 'general'],
            ['name' => 'General', 'order' => 1]
        );

        $contents = [
            // ARTIKEL (5 buah)
            [
                'title' => 'Panduan Lengkap Skincare untuk Kulit Berminyak',
                'type' => 'article',
                'skin_type' => 'oily',
                'xp_reward' => 30,
                'read_time' => 7,
                'is_featured' => true,
                'content' => "Kulit berminyak membutuhkan perhatian khusus untuk mengontrol produksi sebum tanpa membuatnya dehidrasi.\n\n### 1. Gunakan Cleanser Berbasis Gel\nSabun cuci muka berbasis gel sangat efektif mengangkat kotoran tanpa menyumbat pori-pori.\n\n### 2. Jangan Lewatkan Pelembap\nBanyak yang mengira kulit berminyak tidak butuh pelembap. Itu salah besar! Gunakan pelembap berbahan dasar air (water-based) atau gel.\n\n### 3. Eksfoliasi Rutin\nGunakan BHA (Salicylic Acid) 2-3 kali seminggu untuk membersihkan pori-pori dari dalam.",
            ],
            [
                'title' => 'Kenali Bahan Aktif Skincare yang Wajib Kamu Tahu',
                'type' => 'article',
                'skin_type' => 'all',
                'xp_reward' => 30,
                'read_time' => 5,
                'is_featured' => false,
                'content' => "Dunia skincare penuh dengan istilah ilmiah. Mari kita bahas beberapa bahan aktif paling populer:\n\n**1. Niacinamide**\nBahan serba bisa yang cocok untuk mencerahkan, mengontrol minyak, dan memperkuat skin barrier.\n\n**2. Retinol**\nBahan anti-aging terbaik, tapi harus digunakan dengan hati-hati oleh pemula.\n\n**3. Vitamin C**\nAntioksidan kuat untuk mencerahkan wajah dan melindungi dari radikal bebas.",
            ],
            [
                'title' => 'Cara Membangun Skincare Routine untuk Pemula',
                'type' => 'article',
                'skin_type' => 'all',
                'xp_reward' => 30,
                'read_time' => 6,
                'is_featured' => true,
                'content' => "Memulai skincare routine tidak perlu rumit. Cukup ikuti metode CTMP:\n\n1. **Cleansing**: Bersihkan wajah dengan sabun muka yang lembut.\n2. **Toning**: (Opsional) kembalikan pH kulit.\n3. **Moisturizing**: Kunci kelembapan kulit.\n4. **Protecting**: Gunakan Sunscreen di pagi hari. Ini adalah langkah PALING penting!",
            ],
            [
                'title' => 'Skincare untuk Kulit Sensitif: Dos and Don\'ts',
                'type' => 'article',
                'skin_type' => 'sensitive',
                'xp_reward' => 30,
                'read_time' => 4,
                'is_featured' => false,
                'content' => "Kulit sensitif mudah merah dan gatal. Berikut panduannya:\n\n**Do's:**\n- Pilih produk fragrance-free (tanpa parfum).\n- Lakukan patch test sebelum mencoba produk baru.\n- Gunakan bahan yang menenangkan seperti Centella Asiatica atau Ceramide.\n\n**Don'ts:**\n- Hindari eksfoliasi fisik (scrub) yang kasar.\n- Jangan terlalu sering ganti-ganti produk skincare.",
            ],
            [
                'title' => 'Double Cleansing: Mengapa Penting dan Cara Melakukannya',
                'type' => 'article',
                'skin_type' => 'all',
                'xp_reward' => 30,
                'read_time' => 3,
                'is_featured' => false,
                'content' => "Double cleansing adalah rahasia kulit bersih maksimal, terutama jika kamu menggunakan makeup atau sunscreen setiap hari.\n\n**Langkah 1 (First Cleanser):**\nGunakan Micellar Water, Cleansing Balm, atau Cleansing Oil untuk meluruhkan kotoran dan makeup.\n\n**Langkah 2 (Second Cleanser):**\nGunakan sabun cuci muka biasa (facial wash) untuk membersihkan sisa kotoran.",
            ],

            // TIPS (4 buah)
            [
                'title' => '5 Kesalahan Skincare yang Sering Dilakukan',
                'type' => 'tip',
                'skin_type' => 'all',
                'xp_reward' => 10,
                'read_time' => 2,
                'is_featured' => true,
                'content' => "1. Tidak pakai sunscreen saat mendung.\n2. Menggosok wajah terlalu keras dengan handuk.\n3. Memakai terlalu banyak produk sekaligus.\n4. Memencet jerawat sendiri.\n5. Tidur dengan makeup masih menempel.",
            ],
            [
                'title' => 'Tips Skincare Minimalis tapi Efektif',
                'type' => 'tip',
                'skin_type' => 'all',
                'xp_reward' => 10,
                'read_time' => 2,
                'is_featured' => false,
                'content' => "Skincare tidak harus berlapis-lapis. Kamu hanya butuh 3 hal esensial: Cleanser, Moisturizer, dan Sunscreen. Pastikan ketiga produk ini bekerja dengan baik untuk kulitmu sebelum menambah serum atau toner.",
            ],
            [
                'title' => 'Cara Pakai Sunscreen yang Benar',
                'type' => 'tip',
                'skin_type' => 'all',
                'xp_reward' => 10,
                'read_time' => 1,
                'is_featured' => false,
                'content' => "Gunakan aturan 'Dua Jari'. Aplikasikan sunscreen sepanjang jari telunjuk dan jari tengah untuk menutupi seluruh wajah dan leher secara merata. Jangan lupa reapply setiap 3-4 jam jika banyak beraktivitas di luar.",
            ],
            [
                'title' => 'Tips Kulit Glowing Alami Tanpa Makeup',
                'type' => 'tip',
                'skin_type' => 'normal', // dry, normal (kita set normal)
                'xp_reward' => 10,
                'read_time' => 2,
                'is_featured' => true,
                'content' => "Selain skincare, gaya hidup sangat menentukan glow alaminya kulitmu.\n- Minum minimal 2 liter air putih sehari.\n- Tidur cukup 7-8 jam.\n- Kurangi konsumsi gula berlebih, karena bisa memicu glycation yang merusak kolagen.",
            ],

            // VIDEO (3 buah)
            [
                'title' => 'Skincare Routine Pagi untuk Kulit Berminyak',
                'type' => 'video',
                'skin_type' => 'oily',
                'xp_reward' => 50,
                'read_time' => 0,
                'is_featured' => true,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'Dalam video ini, kita akan membahas step-by-step skincare routine di pagi hari khusus untuk kamu yang punya kulit berminyak, agar bebas kilap seharian!',
            ],
            [
                'title' => 'Cara Pakai Serum yang Benar',
                'type' => 'video',
                'skin_type' => 'all',
                'xp_reward' => 50,
                'read_time' => 0,
                'is_featured' => false,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'Serum adalah konsentrat bahan aktif. Tonton video ini untuk mengetahui cara aplikasi serum yang tepat agar menyerap maksimal ke dalam kulit.',
            ],
            [
                'title' => 'Review Kandungan Skincare Populer',
                'type' => 'video',
                'skin_type' => 'all',
                'xp_reward' => 50,
                'read_time' => 0,
                'is_featured' => false,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'Niacinamide, Salicylic Acid, AHA, BHA... bingung? Tonton panduan lengkap mengenal kandungan skincare populer ini.',
            ],
        ];

        foreach ($contents as $data) {
            $data['category_id'] = $category->id;
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
            $data['is_published'] = true;
            SkinContent::create($data);
        }
    }
}
