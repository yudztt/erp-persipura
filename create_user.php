<?php
/**
 * Script untuk membuat user admin
 * Jalankan: php create_user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "===========================================\n";
echo "  CREATE ADMIN USER - PERSIPURA ERP\n";
echo "===========================================\n\n";

try {
    // Create role
    echo "Creating role...\n";
    $role = Role::firstOrCreate(
        ['nama_role' => 'System Admin'],
        ['deskripsi' => 'Administrator dengan akses penuh']
    );
    echo "✓ Role created: {$role->nama_role}\n\n";

    // Create user
    echo "Creating admin user...\n";
    $user = User::updateOrCreate(
        ['email' => 'admin@persipura.id'],
        [
            'id_role' => $role->id_role,
            'nama' => 'Admin Persipura',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]
    );
    
    echo "✓ User created successfully!\n\n";
    echo "===========================================\n";
    echo "  LOGIN CREDENTIALS\n";
    echo "===========================================\n";
    echo "Email    : admin@persipura.id\n";
    echo "Password : admin123\n";
    echo "===========================================\n\n";
    echo "✓ Sekarang Anda bisa login!\n";
    echo "  Buka: http://127.0.0.1:8000\n\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nPastikan:\n";
    echo "1. Database 'persipura_erp' sudah dibuat\n";
    echo "2. Migration sudah dijalankan (php artisan migrate)\n";
    echo "3. MySQL di XAMPP sudah running\n";
}
