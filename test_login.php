<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== TESTING LOGIN FUNCTIONALITY ===\n\n";

try {
    // Test 1: Check if User model works
    echo "1. Testing User model...\n";
    $user = User::where('email', 'admin@persipura.id')->first();
    
    if ($user) {
        echo "✓ User found: {$user->nama}\n";
        echo "✓ Primary key: {$user->getKeyName()} = {$user->getKey()}\n";
        echo "✓ Auth identifier: {$user->getAuthIdentifierName()}\n";
    } else {
        echo "✗ User not found!\n";
        exit(1);
    }
    
    // Test 2: Test Auth attempt
    echo "\n2. Testing Auth::attempt...\n";
    $credentials = [
        'email' => 'admin@persipura.id',
        'password' => 'admin123'
    ];
    
    $result = Auth::attempt($credentials);
    
    if ($result) {
        echo "✓ Auth::attempt successful!\n";
        $authUser = Auth::user();
        echo "✓ Authenticated user: {$authUser->nama}\n";
        echo "✓ User ID: {$authUser->getKey()}\n";
        
        // Logout for clean test
        Auth::logout();
        echo "✓ Logged out successfully\n";
    } else {
        echo "✗ Auth::attempt failed!\n";
        
        // Debug: Check what's happening
        echo "\nDEBUG INFO:\n";
        $user = User::where('email', $credentials['email'])->first();
        if ($user) {
            echo "- User exists: YES\n";
            echo "- Password check: " . (password_verify($credentials['password'], $user->password) ? 'YES' : 'NO') . "\n";
        } else {
            echo "- User exists: NO\n";
        }
    }
    
    echo "\n=== TEST COMPLETE ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}