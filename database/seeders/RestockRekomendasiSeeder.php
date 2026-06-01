<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RestockRekomendasiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get available product and prediksi IDs
        $produkIds = DB::table('produks')->pluck('id_produk')->toArray();
        $prediksiIds = DB::table('prediksi_penjualans')->pluck('id_prediksi')->toArray();
        
        if (empty($produkIds) || empty($prediksiIds)) {
            echo "Error: Tidak ada data produk atau prediksi yang tersedia\n";
            return;
        }
        
        // Generate restock recommendations
        $statusOptions = ['pending', 'approved', 'rejected', 'completed'];
        
        $jumlahRekomendasi = min(25, count($prediksiIds)); // Max 25 or all prediksi if less
        
        for ($i = 1; $i <= $jumlahRekomendasi; $i++) {
            $produkId = $faker->randomElement($produkIds);
            $prediksiId = $faker->randomElement($prediksiIds);
            $status = $faker->randomElement($statusOptions);
            
            // Get current stock from produk (simulate)
            $produk = DB::table('produks')->where('id_produk', $produkId)->first();
            $stokSaatIni = $produk ? $produk->stok : $faker->numberBetween(5, 50);
            $stokDisarankan = $stokSaatIni + $faker->numberBetween(20, 100);
            
            DB::table('restock_rekomendasis')->insert([
                'id_produk' => $produkId,
                'id_prediksi' => $prediksiId,
                'stok_saat_ini' => $stokSaatIni,
                'stok_disarankan' => $stokDisarankan,
                'status' => $status,
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}