<?php

namespace Database\Seeders;

use App\Models\KareblaProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocalKareblaSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'       => 'Karebla Tumbler Signature',
                'collection' => 'Karebla Premium Collection',
                'coin_price' => 50,
                'stock'      => 45,
                'badge'      => 'terlaris',
                'description'=> 'Tumbler eksklusif Karebla dengan desain elegan. Terbuat dari material food-grade premium yang menjaga suhu minuman dingin hingga 24 jam dan panas 12 jam.',
                'specs'      => [
                    'Material'  => 'Stainless Steel 316',
                    'Kapasitas' => '500ml',
                    'Warna'     => 'Signature Blend',
                    'Fitur'     => 'Vacuum Insulated',
                ],
                'images'     => ['images/karebla/karebla1.jpeg'],
            ],
            [
                'name'       => 'Karebla Eco Bamboo Flask',
                'collection' => 'Karebla Eco Collection',
                'coin_price' => 35,
                'stock'      => 60,
                'badge'      => 'baru',
                'description'=> 'Thermos ramah lingkungan dengan lapisan luar bambu alami. Solusi sempurna untuk gaya hidup hijau yang estetis.',
                'specs'      => [
                    'Material'  => 'Bambu & Stainless Steel',
                    'Kapasitas' => '450ml',
                    'Desain'    => 'Natural Wood',
                    'Fitur'     => 'Anti Bocor',
                ],
                'images'     => ['images/karebla/karebla2.jpeg'],
            ],
            [
                'name'       => 'Karebla Minimalist Tumbler',
                'collection' => 'Karebla Lifestyle Collection',
                'coin_price' => 40,
                'stock'      => 50,
                'badge'      => 'populer',
                'description'=> 'Desain simpel, elegan, dan fungsional. Sangat cocok menemani aktivitas padatmu di kantor maupun saat gym.',
                'specs'      => [
                    'Material'  => 'Stainless Steel 304',
                    'Kapasitas' => '600ml',
                    'Warna'     => 'Matte Finish',
                    'Fitur'     => 'Tutup Flip',
                ],
                'images'     => ['images/karebla/karebla3.jpeg'],
            ],
            [
                'name'       => 'Karebla Travel Thermos',
                'collection' => 'Karebla Travel Series',
                'coin_price' => 55,
                'stock'      => 30,
                'badge'      => 'eksklusif',
                'description'=> 'Didesain khusus untuk para traveler. Memiliki lapisan dinding ganda (double-wall) dengan durabilitas maksimal anti-penyok.',
                'specs'      => [
                    'Material'  => 'Ultra-tough Stainless',
                    'Kapasitas' => '750ml',
                    'Warna'     => 'Deep Solid',
                    'Fitur'     => 'Double Wall Insulation',
                ],
                'images'     => ['images/karebla/karebla4.jpeg'],
            ],
            [
                'name'       => 'Karebla Coffee Cup Smart',
                'collection' => 'Karebla Smart Collection',
                'coin_price' => 45,
                'stock'      => 40,
                'badge'      => 'terlaris',
                'description'=> 'Gelas kopi kekinian dengan insulator pintar yang membuat suhu kopimu pas lebih lama tanpa membuat tangan kepanasan.',
                'specs'      => [
                    'Material'  => 'BPA-Free Tritan & Stainless',
                    'Kapasitas' => '350ml',
                    'Warna'     => 'Pastel',
                    'Fitur'     => 'Ergonomic Grip',
                ],
                'images'     => ['images/karebla/karebla5.jpeg'],
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            $product['is_active'] = true;
            KareblaProduct::firstOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
