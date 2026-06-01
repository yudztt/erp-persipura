<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== CHECKING USERS IN DATABASE ===\n\n";

try {
    $users = DB::table('users')->get(['id_user', 'nama', 'email', 'password']);
    
    if ($users->count() > 0) {
        echo "Found " . $users->count() . " users:\n\n";
        
        foreach ($users as $user) {
            echo "ID: {$user->id_user}\n";
            echo "Name: {$user->nama}\n";
            echo "Email: {$user->email}\n";
            echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
            
            // Test password
            $testPassword = 'admin123';
            $isValid = Hash::check($testPassword, $user->password);
            echo "Password 'admin123' valid: " . ($isValid ? 'YES' : 'NO') . "\n";
            echo "---\n";
        }
        
        // Test login attempt
        echo "\n=== TESTING LOGIN ATTEMPT ===\n";
        $credentials = [
            'email' => 'admin@persipura.id',
            'password' => 'admin123'
        ];
        
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        if ($user) {
            echo "User found: {$user->nama}\n";
            $passwordMatch = Hash::check($credentials['password'], $user->password);
            echo "Password match: " . ($passwordMatch ? 'YES' : 'NO') . "\n";
        } else {
            echo "User not found with email: {$credentials['email']}\n";
        }
        
    } else {
        echo "No users found in database!\n";
        echo "Please run: php artisan db:seed\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK COMPLETE ===\n";