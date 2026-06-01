<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            ProdukSeeder::class,
            PembelianSeeder::class,
            PenjualanSeeder::class,
            PrediksiPenjualanSeeder::class,
            RestockRekomendasiSeeder::class,
        ]);
    }
}
