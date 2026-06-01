<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== SIMPLE LOGIN TEST ===\n\n";

// Test basic authentication
$credentials = [
    'email' => 'admin@persipura.id',
    'password' => 'admin123'
];

echo "Testing login with:\n";
echo "Email: {$credentials['email']}\n";
echo "Password: {$credentials['password']}\n\n";

$success = Auth::attempt($credentials);

if ($success) {
    echo "✅ LOGIN SUCCESS!\n";
    echo "User: " . Auth::user()->nama . "\n";
    echo "Email: " . Auth::user()->email . "\n";
    echo "ID: " . Auth::user()->id_user . "\n";
} else {
    echo "❌ LOGIN FAILED!\n";
    
    // Check if user exists
    $user = User::where('email', $credentials['email'])->first();
    if ($user) {
        echo "User exists: YES\n";
        echo "Password hash: " . substr($user->password, 0, 20) . "...\n";
        
        // Manual password check
        if (password_verify($credentials['password'], $user->password)) {
            echo "Password verification: SUCCESS\n";
            echo "Issue might be with Laravel Auth configuration\n";
        } else {
            echo "Password verification: FAILED\n";
        }
    } else {
        echo "User exists: NO\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";