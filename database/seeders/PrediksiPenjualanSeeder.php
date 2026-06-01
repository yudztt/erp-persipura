<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PrediksiPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get available product IDs
        $produkIds = DB::table('produks')->pluck('id_produk')->toArray();
        
        if (empty($produkIds)) {
            echo "Error: Tidak ada data produk yang tersedia\n";
            return;
        }
        
        // Generate prediksi for next 12 months for top products
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $topProdukIds = array_slice($produkIds, 0, min(20, count($produkIds))); // Take first 20 or all if less
        
        for ($month = 1; $month <= 12; $month++) {
            $targetMonth = $currentMonth + $month;
            $targetYear = $currentYear;
            
            // Handle year overflow
            if ($targetMonth > 12) {
                $targetYear++;
                $targetMonth = $targetMonth - 12;
            }
            
            $tanggalPrediksi = now()->setYear($targetYear)->setMonth($targetMonth)->setDay(1);
            
            foreach ($topProdukIds as $produkId) {
                // Base prediction with seasonal variations
                $basePrediction = $faker->numberBetween(10, 100);
                
                // Add seasonal boost for certain months (holiday seasons)
                if (in_array($targetMonth, [6, 7, 12])) { // Mid year and end year
                    $basePrediction = $basePrediction * 1.5;
                }
                
                // Add some randomness
                $hasilPrediksi = $basePrediction + $faker->numberBetween(-10, 20);
                $hasilPrediksi = max(1, $hasilPrediksi); // Ensure minimum 1
                
                DB::table('prediksi_penjualans')->insert([
                    'id_produk' => $produkId,
                    'bulan' => $targetMonth,
                    'tahun' => $targetYear,
                    'hasil_prediksi' => $hasilPrediksi,
                    'tanggal_prediksi' => $tanggalPrediksi->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}