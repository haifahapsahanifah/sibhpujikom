<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pengguna');
    }

    /**
     * Display user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil semua permintaan user
        $permintaans = PermintaanBarang::with('details')
            ->where('user_id', Auth::id())
            ->get();
        
        // Hitung statistik
        $totalPermintaan = $permintaans->count();
        $disetujui = $permintaans->whereIn('status', ['disetujui', 'selesai'])->count();
        $menunggu = $permintaans->where('status', 'menunggu_admin')->count();
        
        // Ambil 5 permintaan terbaru
        $recentPermintaan = PermintaanBarang::with('details')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('user.dashboard', compact(
            'user',
            'totalPermintaan',
            'disetujui',
            'menunggu',
            'recentPermintaan'
        ));
    }

    /**
     * Display user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'bidang' => 'required|string|max:255',
            'nip' => 'required|string|max:18|unique:users,nip,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->bidang = $request->bidang;
        $user->nip = $request->nip;
        $user->save();
        
        return redirect()->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}