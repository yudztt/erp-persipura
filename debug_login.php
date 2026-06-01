<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== DEBUG LOGIN PROCESS ===\n\n";

// Simulate login request
$request = Request::create('/login', 'POST', [
    'email' => 'admin@persipura.id',
    'password' => 'admin123',
    '_token' => 'test-token'
]);

// Start session
$app->make('session')->start();

echo "1. Testing credentials validation...\n";
$credentials = [
    'email' => 'admin@persipura.id',
    'password' => 'admin123'
];

// Validate credentials manually
$user = User::where('email', $credentials['email'])->first();
if ($user && password_verify($credentials['password'], $user->password)) {
    echo "✓ Credentials are valid\n";
} else {
    echo "✗ Credentials are invalid\n";
    exit(1);
}

echo "\n2. Testing Auth::attempt...\n";
$result = Auth::attempt($credentials);

if ($result) {
    echo "✓ Auth::attempt successful\n";
    echo "✓ User authenticated: " . Auth::user()->nama . "\n";
    echo "✓ User ID: " . Auth::id() . "\n";
    echo "✓ Auth check: " . (Auth::check() ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "✗ Auth::attempt failed\n";
}

echo "\n3. Session information...\n";
$session = app('session');
echo "Session ID: " . $session->getId() . "\n";
echo "Session started: " . ($session->isStarted() ? 'YES' : 'NO') . "\n";

echo "\n=== DEBUG COMPLETE ===\n";