<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete existing data (safer than truncate with foreign keys)
        DB::table('users')->delete();
        DB::table('roles')->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert Roles
        $adminRoleId = DB::table('roles')->insertGetId([
            'nama_role' => 'System Admin',
            'deskripsi' => 'Administrator dengan akses penuh',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerRoleId = DB::table('roles')->insertGetId([
            'nama_role' => 'Warehouse Manager',
            'deskripsi' => 'Manager gudang',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $salesRoleId = DB::table('roles')->insertGetId([
            'nama_role' => 'Sales Staff',
            'deskripsi' => 'Staff penjualan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $inventoryRoleId = DB::table('roles')->insertGetId([
            'nama_role' => 'Inventory Staff',
            'deskripsi' => 'Staff inventory',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Users
        DB::table('users')->insert([
            [
                'id_role' => $adminRoleId,
                'nama' => 'Admin Persipura',
                'email' => 'admin@persipura.id',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => $managerRoleId,
                'nama' => 'Siti Maryam',
                'email' => 'manager@persipura.id',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => $salesRoleId,
                'nama' => 'Rudi Kurniawan',
                'email' => 'sales@persipura.id',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => $inventoryRoleId,
                'nama' => 'Dewi Puspita',
                'email' => 'inventory@persipura.id',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "\n✓ Seeder berhasil dijalankan!\n";
        echo "✓ 4 roles dan 4 users telah dibuat\n\n";
        echo "Login dengan:\n";
        echo "Email: admin@persipura.id\n";
        echo "Password: admin123\n\n";
    }
}
