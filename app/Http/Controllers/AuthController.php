<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // Redirect to dashboard if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Debug: Log the attempt
        \Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Remember me option
        $remember = $request->has('remember');

        // Debug: Check if user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        if (!$user) {
            \Log::warning('Login failed: User not found', ['email' => $credentials['email']]);
            return back()
                ->withErrors(['email' => 'Email tidak ditemukan'])
                ->withInput($request->only('email'));
        }

        // Debug: Check password
        if (!\Hash::check($credentials['password'], $user->password)) {
            \Log::warning('Login failed: Wrong password', ['email' => $credentials['email']]);
            return back()
                ->withErrors(['email' => 'Email atau password salah'])
                ->withInput($request->only('email'));
        }

        // Attempt to login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Get authenticated user
            $user = Auth::user();
            
            // Log success
            \Log::info('Login successful', [
                'user_id' => $user->id_user,
                'email' => $user->email,
                'name' => $user->nama
            ]);
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . $user->nama . '!');
        }

        // This should not happen if we reach here
        \Log::error('Auth::attempt failed despite manual checks passing', [
            'email' => $credentials['email']
        ]);

        // Login failed
        return back()
            ->withErrors(['email' => 'Terjadi kesalahan sistem. Silakan coba lagi.'])
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout');
    }
}
