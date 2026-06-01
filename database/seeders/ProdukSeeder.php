<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $produkData = [
            // Jersey Persipura (kategori 1)
            ['nama' => 'Jersey Home Persipura 2026', 'kategori' => 1, 'harga_beli' => 180000, 'harga_jual' => 250000],
            ['nama' => 'Jersey Away Persipura 2026', 'kategori' => 1, 'harga_beli' => 180000, 'harga_jual' => 250000],
            ['nama' => 'Jersey Third Persipura 2026', 'kategori' => 1, 'harga_beli' => 180000, 'harga_jual' => 250000],
            ['nama' => 'Jersey Retro Persipura 1990', 'kategori' => 1, 'harga_beli' => 200000, 'harga_jual' => 300000],
            ['nama' => 'Jersey Training Persipura', 'kategori' => 1, 'harga_beli' => 120000, 'harga_jual' => 180000],
            
            // Kaos Casual (kategori 2)
            ['nama' => 'Kaos Persipura Logo Classic', 'kategori' => 2, 'harga_beli' => 60000, 'harga_jual' => 95000],
            ['nama' => 'Kaos Persipura Mutiara Hitam', 'kategori' => 2, 'harga_beli' => 65000, 'harga_jual' => 100000],
            ['nama' => 'Kaos Persipura Papua Pride', 'kategori' => 2, 'harga_beli' => 70000, 'harga_jual' => 110000],
            ['nama' => 'Kaos Vintage Persipura 1963', 'kategori' => 2, 'harga_beli' => 75000, 'harga_jual' => 120000],
            ['nama' => 'Kaos Persipura Cendrawasih', 'kategori' => 2, 'harga_beli' => 68000, 'harga_jual' => 105000],
            
            // Jaket & Hoodie (kategori 3)
            ['nama' => 'Jaket Varsity Persipura', 'kategori' => 3, 'harga_beli' => 250000, 'harga_jual' => 380000],
            ['nama' => 'Hoodie Persipura Black Gold', 'kategori' => 3, 'harga_beli' => 180000, 'harga_jual' => 280000],
            ['nama' => 'Jaket Windbreaker Persipura', 'kategori' => 3, 'harga_beli' => 200000, 'harga_jual' => 320000],
            ['nama' => 'Hoodie Zip Persipura Classic', 'kategori' => 3, 'harga_beli' => 190000, 'harga_jual' => 290000],
            ['nama' => 'Jaket Bomber Persipura Limited', 'kategori' => 3, 'harga_beli' => 300000, 'harga_jual' => 450000],
            
            // Celana Training (kategori 4)
            ['nama' => 'Celana Training Persipura Home', 'kategori' => 4, 'harga_beli' => 120000, 'harga_jual' => 180000],
            ['nama' => 'Celana Training Persipura Away', 'kategori' => 4, 'harga_beli' => 120000, 'harga_jual' => 180000],
            ['nama' => 'Celana Jogger Persipura Casual', 'kategori' => 4, 'harga_beli' => 100000, 'harga_jual' => 150000],
            ['nama' => 'Celana Pendek Training Persipura', 'kategori' => 4, 'harga_beli' => 80000, 'harga_jual' => 125000],
            ['nama' => 'Celana Track Persipura Premium', 'kategori' => 4, 'harga_beli' => 140000, 'harga_jual' => 210000],
            
            // Sepatu Futsal (kategori 5)
            ['nama' => 'Sepatu Futsal Persipura Pro', 'kategori' => 5, 'harga_beli' => 350000, 'harga_jual' => 520000],
            ['nama' => 'Sepatu Futsal Persipura Elite', 'kategori' => 5, 'harga_beli' => 450000, 'harga_jual' => 680000],
            ['nama' => 'Sepatu Futsal Persipura Junior', 'kategori' => 5, 'harga_beli' => 250000, 'harga_jual' => 380000],
            ['nama' => 'Sepatu Futsal Persipura Classic', 'kategori' => 5, 'harga_beli' => 300000, 'harga_jual' => 450000],
            ['nama' => 'Sepatu Futsal Persipura Limited Edition', 'kategori' => 5, 'harga_beli' => 500000, 'harga_jual' => 750000],
            
            // Sepatu Casual (kategori 6)
            ['nama' => 'Sneakers Persipura Street', 'kategori' => 6, 'harga_beli' => 280000, 'harga_jual' => 420000],
            ['nama' => 'Sepatu Canvas Persipura', 'kategori' => 6, 'harga_beli' => 180000, 'harga_jual' => 270000],
            ['nama' => 'Sepatu Slip On Persipura', 'kategori' => 6, 'harga_beli' => 200000, 'harga_jual' => 300000],
            ['nama' => 'High Top Persipura Classic', 'kategori' => 6, 'harga_beli' => 320000, 'harga_jual' => 480000],
            ['nama' => 'Running Shoes Persipura', 'kategori' => 6, 'harga_beli' => 380000, 'harga_jual' => 570000],
            
            // Aksesoris (kategori 7)
            ['nama' => 'Gelang Persipura Silikon', 'kategori' => 7, 'harga_beli' => 15000, 'harga_jual' => 25000],
            ['nama' => 'Kalung Persipura Logo', 'kategori' => 7, 'harga_beli' => 45000, 'harga_jual' => 70000],
            ['nama' => 'Jam Tangan Persipura Digital', 'kategori' => 7, 'harga_beli' => 180000, 'harga_jual' => 280000],
            ['nama' => 'Dompet Persipura Kulit', 'kategori' => 7, 'harga_beli' => 120000, 'harga_jual' => 180000],
            ['nama' => 'Ikat Pinggang Persipura', 'kategori' => 7, 'harga_beli' => 80000, 'harga_jual' => 125000],
        ];

        $counter = 1;
        foreach ($produkData as $produk) {
            $stok = $faker->numberBetween(10, 100);
            $stokMinimum = $faker->numberBetween(5, 20);
            
            DB::table('produks')->insert([
                'id_kategori' => $produk['kategori'],
                'id_supplier' => $faker->numberBetween(1, 15),
                'kode_produk' => 'PSP' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                'nama_produk' => $produk['nama'],
                'harga_beli' => $produk['harga_beli'],
                'harga_jual' => $produk['harga_jual'],
                'stok' => $stok,
                'stok_minimum' => $stokMinimum,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
            $counter++;
        }

        // Generate additional random products for other categories
        $kategoriLain = [8, 9, 10, 11, 12, 13, 14, 15]; // Tas, Topi, Kaos Kaki, dll
        $namaAksesoris = [
            'Tas Ransel Persipura Adventure',
            'Tas Gym Persipura Sport',
            'Tas Selempang Persipura Casual',
            'Topi Baseball Persipura Classic',
            'Topi Snapback Persipura Street',
            'Kupluk Persipura Winter',
            'Kaos Kaki Persipura Home',
            'Kaos Kaki Persipura Away',
            'Kaos Kaki Persipura Training',
            'Sarung Tangan Kiper Persipura',
            'Sarung Tangan Winter Persipura',
            'Syal Persipura Supporter',
            'Scarf Persipura Premium',
            'Pin Persipura Logo Enamel',
            'Badge Persipura Vintage',
            'Pin Set Persipura Collection',
            'Gantungan Kunci Persipura 3D',
            'Gantungan Kunci Persipura Rubber',
            'Keychain Persipura Metal',
            'Stiker Persipura Waterproof',
            'Decal Persipura Reflective',
            'Stiker Set Persipura Fan',
        ];

        foreach ($namaAksesoris as $index => $nama) {
            $kategoriId = $kategoriLain[$index % count($kategoriLain)];
            $hargaBeli = $faker->numberBetween(10000, 150000);
            $hargaJual = $hargaBeli * $faker->randomFloat(2, 1.5, 2.5);
            
            DB::table('produks')->insert([
                'id_kategori' => $kategoriId,
                'id_supplier' => $faker->numberBetween(1, 15),
                'kode_produk' => 'PSP' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                'nama_produk' => $nama,
                'harga_beli' => $hargaBeli,
                'harga_jual' => $hargaJual,
                'stok' => $faker->numberBetween(5, 80),
                'stok_minimum' => $faker->numberBetween(3, 15),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
            $counter++;
        }
    }
}