<?php

/**
 * Script untuk menjalankan database seeders
 * Jalankan dengan: php run_seeders.php
 */

echo "=== ERP PERSIPURA DATABASE SEEDER ===\n";
echo "Memulai proses seeding database...\n\n";

// Jalankan migration terlebih dahulu
echo "1. Menjalankan migrations...\n";
exec('php artisan migrate:fresh', $output, $return_var);

if ($return_var !== 0) {
    echo "Error: Migration gagal!\n";
    exit(1);
}

echo "   ✓ Migrations berhasil dijalankan\n\n";

// Jalankan seeders
echo "2. Menjalankan seeders...\n";
exec('php artisan db:seed', $output, $return_var);

if ($return_var !== 0) {
    echo "Error: Seeding gagal!\n";
    exit(1);
}

echo "   ✓ Seeders berhasil dijalankan\n\n";

echo "=== SEEDING SELESAI ===\n";
echo "Database telah diisi dengan data dummy:\n";
echo "- 15 Kategori produk\n";
echo "- 15 Supplier\n";
echo "- 50 Customer\n";
echo "- 55+ Produk Persipura\n";
echo "- 30 Transaksi Pembelian\n";
echo "- 80 Transaksi Penjualan\n";
echo "- 240 Prediksi Penjualan (12 bulan)\n";
echo "- 25 Rekomendasi Restock\n\n";
echo "Website ERP Persipura siap digunakan dengan data dinamis!\n";