<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Jersey Persipura'],
            ['nama_kategori' => 'Kaos Casual'],
            ['nama_kategori' => 'Jaket & Hoodie'],
            ['nama_kategori' => 'Celana Training'],
            ['nama_kategori' => 'Sepatu Futsal'],
            ['nama_kategori' => 'Sepatu Casual'],
            ['nama_kategori' => 'Aksesoris'],
            ['nama_kategori' => 'Tas & Ransel'],
            ['nama_kategori' => 'Topi & Kupluk'],
            ['nama_kategori' => 'Kaos Kaki'],
            ['nama_kategori' => 'Sarung Tangan'],
            ['nama_kategori' => 'Syal & Scarf'],
            ['nama_kategori' => 'Pin & Badge'],
            ['nama_kategori' => 'Gantungan Kunci'],
            ['nama_kategori' => 'Stiker & Decal'],
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategoris')->insert([
                'nama_kategori' => $kategori['nama_kategori'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}