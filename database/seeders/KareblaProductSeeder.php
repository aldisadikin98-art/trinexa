<?php

namespace Database\Seeders;

use App\Models\KareblaProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KareblaProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'       => 'Tumbler Gold 500ml',
                'collection' => 'Karebla Premium Collection',
                'coin_price' => 50,
                'stock'      => 20,
                'badge'      => 'eksklusif',
                'description'=> 'Tumbler premium dengan desain elegan dan material berkualitas tinggi. Menjaga suhu minuman tetap hangat atau dingin berjam-jam.',
                'specs'      => [
                    'Material'  => 'Stainless Steel 304',
                    'Kapasitas' => '500ml',
                    'Warna'     => 'Gold',
                    'Tinggi'    => '25cm',
                ],
                'images'     => [
                    'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'name'       => 'Eco Tote Bag',
                'collection' => 'Karebla Lifestyle Collection',
                'coin_price' => 30,
                'stock'      => 35,
                'badge'      => 'terlaris',
                'description'=> 'Tote bag ramah lingkungan dari kanvas organik yang kuat dan tahan lama. Teman setia untuk belanja harianmu.',
                'specs'      => [
                    'Material' => 'Canvas Organik',
                    'Ukuran'   => '35x40cm',
                    'Warna'    => 'Cream',
                ],
                'images'     => [
                    'https://images.unsplash.com/photo-1597484661643-2f5fef640df1?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'name'       => 'Bamboo Straw Set',
                'collection' => 'Karebla Eco Collection',
                'coin_price' => 20,
                'stock'      => 50,
                'badge'      => 'baru',
                'description'=> 'Set sedotan bambu alami lengkap dengan sikat pembersih. Kurangi penggunaan plastik sekali pakai dengan gaya.',
                'specs'      => [
                    'Material' => 'Bambu Alami',
                    'Isi'      => '4 sedotan + sikat pembersih',
                    'Panjang'  => '22cm',
                ],
                'images'     => [
                    'https://images.unsplash.com/photo-1584346851965-0bc5cd42c4db?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'name'       => 'Reusable Makeup Pad',
                'collection' => 'Karebla Beauty Collection',
                'coin_price' => 25,
                'stock'      => 30,
                'badge'      => 'eksklusif',
                'description'=> 'Kapas makeup reusable dari microfiber yang super lembut. Efektif membersihkan wajah dan bisa dicuci berulang kali.',
                'specs'      => [
                    'Material' => 'Microfiber Lembut',
                    'Isi'      => '12 pads + mesh bag',
                    'Diameter' => '8cm',
                ],
                'images'     => [
                    'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?auto=format&fit=crop&q=80&w=800'
                ]
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            KareblaProduct::create($product);
        }
    }
}
