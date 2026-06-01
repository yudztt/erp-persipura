<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get available user IDs
        $userIds = DB::table('users')->pluck('id_user')->toArray();
        $supplierIds = DB::table('suppliers')->pluck('id_supplier')->toArray();
        $produkIds = DB::table('produks')->pluck('id_produk')->toArray();
        
        if (empty($userIds) || empty($supplierIds) || empty($produkIds)) {
            echo "Error: Tidak ada data users, suppliers, atau produk yang tersedia\n";
            return;
        }
        
        // Generate 30 pembelian transactions
        for ($i = 1; $i <= 30; $i++) {
            $tanggal = $faker->dateTimeBetween('-6 months', 'now');
            $supplierId = $faker->randomElement($supplierIds);
            $userId = $faker->randomElement($userIds);
            
            // Insert pembelian header
            $pembelianId = DB::table('pembelians')->insertGetId([
                'id_supplier' => $supplierId,
                'id_user' => $userId,
                'tanggal' => $tanggal->format('Y-m-d'),
                'total' => 0, // Will be updated after inserting details
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);
            
            // Generate 2-5 detail items per pembelian
            $jumlahItem = $faker->numberBetween(2, 5);
            $totalPembelian = 0;
            
            for ($j = 1; $j <= $jumlahItem; $j++) {
                $produkId = $faker->randomElement($produkIds);
                $qty = $faker->numberBetween(5, 50);
                
                // Get actual product price from database
                $produk = DB::table('produks')->where('id_produk', $produkId)->first();
                $hargaBeli = $produk ? $produk->harga_beli : $faker->numberBetween(10000, 500000);
                $subtotal = $qty * $hargaBeli;
                $totalPembelian += $subtotal;
                
                DB::table('detail_pembelians')->insert([
                    'id_pembelian' => $pembelianId,
                    'id_produk' => $produkId,
                    'qty' => $qty,
                    'harga' => $hargaBeli,
                    'subtotal' => $subtotal,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }
            
            // Update total pembelian
            DB::table('pembelians')
                ->where('id_pembelian', $pembelianId)
                ->update(['total' => $totalPembelian]);
        }
    }
}