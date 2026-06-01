<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get available IDs
        $userIds = DB::table('users')->pluck('id_user')->toArray();
        $customerIds = DB::table('customers')->pluck('id_customer')->toArray();
        $produkIds = DB::table('produks')->pluck('id_produk')->toArray();
        
        if (empty($userIds) || empty($customerIds) || empty($produkIds)) {
            echo "Error: Tidak ada data users, customers, atau produk yang tersedia\n";
            return;
        }
        
        // Generate 80 penjualan transactions
        for ($i = 1; $i <= 80; $i++) {
            $tanggal = $faker->dateTimeBetween('-6 months', 'now');
            $customerId = $faker->randomElement($customerIds);
            $userId = $faker->randomElement($userIds);
            
            // Insert penjualan header
            $penjualanId = DB::table('penjualans')->insertGetId([
                'id_customer' => $customerId,
                'id_user' => $userId,
                'tanggal' => $tanggal->format('Y-m-d'),
                'total' => 0, // Will be updated after inserting details
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);
            
            // Generate 1-4 detail items per penjualan
            $jumlahItem = $faker->numberBetween(1, 4);
            $totalPenjualan = 0;
            
            for ($j = 1; $j <= $jumlahItem; $j++) {
                $produkId = $faker->randomElement($produkIds);
                $qty = $faker->numberBetween(1, 10);
                
                // Get actual product selling price from database
                $produk = DB::table('produks')->where('id_produk', $produkId)->first();
                $hargaJual = $produk ? $produk->harga_jual : $faker->numberBetween(25000, 750000);
                $subtotal = $qty * $hargaJual;
                $totalPenjualan += $subtotal;
                
                DB::table('detail_penjualans')->insert([
                    'id_penjualan' => $penjualanId,
                    'id_produk' => $produkId,
                    'qty' => $qty,
                    'harga' => $hargaJual,
                    'subtotal' => $subtotal,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }
            
            // Update total penjualan
            DB::table('penjualans')
                ->where('id_penjualan', $penjualanId)
                ->update(['total' => $totalPenjualan]);
        }
    }
}