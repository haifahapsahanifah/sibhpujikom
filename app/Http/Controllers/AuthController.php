<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $username = $request->username;
        $cacheKey = 'login_attempts_' . $username;
        $lockoutKey = 'login_lockout_' . $username;

        // Cek apakah akun sedang dikunci
        if (Cache::has($lockoutKey)) {
            $remainingSeconds = Cache::get($lockoutKey);
            return back()->withErrors([
                'login' => 'Akun Anda terkunci karena 3 kali percobaan gagal. Silakan coba lagi setelah ' . $remainingSeconds . ' detik, atau hubungi admin.',
            ])->with('lockout_remaining', $remainingSeconds)->onlyInput('username');
        }

        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials)) {
            // Login berhasil - reset percobaan
            Cache::forget($cacheKey);
            Cache::forget($lockoutKey);
            
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        // Login gagal - catat percobaan
        $attempts = Cache::get($cacheKey, 0);
        $attempts++;
        Cache::put($cacheKey, $attempts, now()->addMinutes(30)); // Simpan selama 30 menit

        if ($attempts >= 3) {
            // Kunci akun selama 5 menit (300 detik)
            Cache::put($lockoutKey, 300, now()->addSeconds(300));
            Cache::forget($cacheKey); // Reset attempts setelah lock
            
            return back()->withErrors([
                'login' => 'Anda telah melakukan 3 kali percobaan gagal. Akun Anda terkunci selama 5 menit. Silakan hubungi admin jika masalah berlanjut.',
            ])->with('lockout_time', 300)->onlyInput('username');
        }

        $remainingAttempts = 3 - $attempts;
        return back()->withErrors([
            'username' => "Username atau password salah. Sisa percobaan: {$remainingAttempts} kali lagi sebelum akun terkunci.",
        ])->with('remaining_attempts', $remainingAttempts)->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}