<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $suppliers = [
            [
                'nama_supplier' => 'PT Garuda Sports Indonesia',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'telepon' => '021-5551234'
            ],
            [
                'nama_supplier' => 'CV Jayapura Textile',
                'alamat' => 'Jl. Ahmad Yani No. 45, Jayapura',
                'telepon' => '0967-531234'
            ],
            [
                'nama_supplier' => 'PT Mutiara Papua Garment',
                'alamat' => 'Jl. Raya Sentani Km 8, Jayapura',
                'telepon' => '0967-591567'
            ],
            [
                'nama_supplier' => 'Toko Olahraga Mandiri',
                'alamat' => 'Jl. Pasar Baru No. 67, Jayapura',
                'telepon' => '0967-533456'
            ],
            [
                'nama_supplier' => 'CV Cendrawasih Sports',
                'alamat' => 'Jl. Percetakan Negara No. 89, Jayapura',
                'telepon' => '0967-534567'
            ],
        ];

        // Insert predefined suppliers
        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->insert([
                'nama_supplier' => $supplier['nama_supplier'],
                'alamat' => $supplier['alamat'],
                'telepon' => $supplier['telepon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Generate additional random suppliers
        for ($i = 6; $i <= 15; $i++) {
            DB::table('suppliers')->insert([
                'nama_supplier' => $faker->company . ' Sports',
                'alamat' => $faker->address,
                'telepon' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}