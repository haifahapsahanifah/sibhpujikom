<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\PengeluaranBarang;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistik Pengguna
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPengguna = User::where('role', 'pengguna')->count();
        
        // Statistik Barang
        $totalBarang = Barang::count();
        $barangMasukBulanIni = BarangMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->sum('jumlah');
        
        // Stok Rendah - Hitung dari BarangMasuk dan PengeluaranBarang
        $lowStockItems = collect();
        
        // Ambil semua barang
        $allBarang = Barang::all();
        
        foreach ($allBarang as $barang) {
            // Hitung total barang masuk
            $totalMasuk = BarangMasuk::where('barang_id', $barang->id)->sum('jumlah');
            
            // Hitung total barang keluar
            $totalKeluar = PengeluaranBarang::where('barang_id', $barang->id)->sum('jumlah');
            
            // Hitung stok akhir
            $stokAkhir = $totalMasuk - $totalKeluar;
            
            // Jika stok kurang dari atau sama dengan stok minimal
            if ($stokAkhir <= ($barang->stok_minimal ?? 0)) {
                $lowStockItems->push((object)[
                    'id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'stok' => $stokAkhir,
                    'stok_minimal' => $barang->stok_minimal ?? 0,
                    'satuan' => $barang->satuan
                ]);
            }
        }
        
        // Urutkan dari stok terendah dan ambil 5
        $lowStockItems = $lowStockItems->sortBy('stok')->take(5);
        $lowStock = $lowStockItems->count();
        
        // Permintaan Terbaru
        $recentRequests = PermintaanBarang::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $pendingRequests = PermintaanBarang::where('status', 'menunggu_admin')->count();
        
        // Total Transaksi Bulan Ini
        $totalMasuk = BarangMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->count();
        $totalKeluar = PengeluaranBarang::whereMonth('tanggal_keluar', now()->month)
            ->whereYear('tanggal_keluar', now()->year)
            ->count();
        $totalTransaksi = $totalMasuk + $totalKeluar;
        
        // Data Chart (6 bulan terakhir)
        $labels = [];
        $masuk = [];
        $keluar = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $labels[] = $bulan->format('M Y');
            
            $totalMasukBulan = BarangMasuk::whereMonth('tanggal_masuk', $bulan->month)
                ->whereYear('tanggal_masuk', $bulan->year)
                ->sum('jumlah');
            
            $totalKeluarBulan = PengeluaranBarang::whereMonth('tanggal_keluar', $bulan->month)
                ->whereYear('tanggal_keluar', $bulan->year)
                ->sum('jumlah');
            
            $masuk[] = $totalMasukBulan;
            $keluar[] = $totalKeluarBulan;
        }
        
        $activeUsers = User::where('updated_at', '>=', now()->subDay())->count() ?? 1; // Basic assumption of active users
        
        $chartData = [
            'labels' => $labels,
            'masuk' => $masuk,
            'keluar' => $keluar,
        ];
        
        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalAdmin',
            'totalPengguna',
            'totalBarang',
            'barangMasukBulanIni',
            'lowStockItems',
            'lowStock',
            'recentRequests',
            'pendingRequests',
            'totalTransaksi',
            'chartData',
            'activeUsers'
        ));
    }

    /**
     * Get Chart Data for AJAX requests.
     */
    public function getChartData(Request $request)
    {
        $period = $request->query('period', '6_bulan');
        
        $labels = [];
        $masuk = [];
        $keluar = [];
        
        if ($period == 'tahun_ini') {
            for ($i = 1; $i <= now()->month; $i++) {
                $bulan = now()->month($i);
                $labels[] = $bulan->format('M');
                
                $masuk[] = BarangMasuk::whereMonth('tanggal_masuk', $i)
                    ->whereYear('tanggal_masuk', now()->year)
                    ->sum('jumlah');
                
                $keluar[] = PengeluaranBarang::whereMonth('tanggal_keluar', $i)
                    ->whereYear('tanggal_keluar', now()->year)
                    ->sum('jumlah');
            }
        } else {
            // Default 6 months
            for ($i = 5; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $labels[] = $bulan->format('M Y');
                
                $masuk[] = BarangMasuk::whereMonth('tanggal_masuk', $bulan->month)
                    ->whereYear('tanggal_masuk', $bulan->year)
                    ->sum('jumlah');
                
                $keluar[] = PengeluaranBarang::whereMonth('tanggal_keluar', $bulan->month)
                    ->whereYear('tanggal_keluar', $bulan->year)
                    ->sum('jumlah');
            }
        }

        return response()->json([
            'success' => true,
            'labels' => $labels,
            'masuk' => $masuk,
            'keluar' => $keluar
        ]);
    }

    /**
     * Display admin profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bidang' => 'required|string|max:255',
            'nip' => 'required|string|size:18|unique:users,nip,' . $user->id,
        ], [
            'nip.size' => 'NIP harus terdiri dari 18 digit angka',
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->bidang = $request->bidang;
        $user->nip = $request->nip;
        $user->save();
        
        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Display user management page.
     */
    public function manajemenPengguna()
    {
        $users = User::orderBy('nama')->paginate(10);
        return view('admin.manajemen-pengguna', compact('users'));
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:6',
            'nip' => 'required|string|size:18|unique:users,nip',
            'bidang' => 'required|string|max:255',
            'role' => 'required|in:admin,pengguna',
        ], [
            'nip.size' => 'NIP harus terdiri dari 18 digit angka',
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nip' => $request->nip,
            'bidang' => $request->bidang,
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan',
            'user' => $user
        ], 201);
    }

    /**
     * Update a user.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id . '|max:255',
            'password' => 'nullable|string|min:6',
            'nip' => 'required|string|size:18|unique:users,nip,' . $user->id,
            'bidang' => 'required|string|max:255',
            'role' => 'required|in:admin,pengguna',
        ], [
            'nip.size' => 'NIP harus terdiri dari 18 digit angka',
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->nip = $request->nip;
        $user->bidang = $request->bidang;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
            'user' => $user
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == Auth::id()) {
            return response()->json([
                'success' => false,
                'error' => 'Tidak dapat menghapus akun sendiri'
            ], 400);
        }

        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus'
        ]);
    }

    /**
     * Get user data for editing.
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    
    /**
     * Display kartu persediaan page.
     */
   
// app/Http/Controllers/AdminController.php - Bagian kartuPersediaan

/**
 * Display kartu persediaan page.
 */
public function kartuPersediaan(Request $request)
{
    // Ambil daftar barang yang sudah memiliki barang masuk
    $barangList = Barang::with(['kategori', 'satuan'])
        ->has('barangMasuk')
        ->orderBy('nama_barang')
        ->get();
    
    $barangId = $request->get('barang_id');
    $periode = $request->get('periode', 'bulan_ini');
    $tanggalAwal = $request->get('tanggal_awal');
    $tanggalAkhir = $request->get('tanggal_akhir');
    
    // Set default tanggal berdasarkan periode
    $today = Carbon::now();
    if ($periode == 'custom') {
        $tanggalAwal = $tanggalAwal ? Carbon::parse($tanggalAwal) : $today->copy()->startOfMonth();
        $tanggalAkhir = $tanggalAkhir ? Carbon::parse($tanggalAkhir) : $today;
    } elseif ($periode == 'bulan_ini') {
        $tanggalAwal = $today->copy()->startOfMonth();
        $tanggalAkhir = $today->copy()->endOfMonth();
    } elseif ($periode == '3_bulan') {
        $tanggalAwal = $today->copy()->subMonths(2)->startOfMonth();
        $tanggalAkhir = $today;
    } elseif ($periode == '6_bulan') {
        $tanggalAwal = $today->copy()->subMonths(5)->startOfMonth();
        $tanggalAkhir = $today;
    } elseif ($periode == 'tahun_ini') {
        $tanggalAwal = $today->copy()->startOfYear();
        $tanggalAkhir = $today;
    } else {
        $tanggalAwal = $today->copy()->startOfMonth();
        $tanggalAkhir = $today->copy()->endOfMonth();
    }
    
    // Inisialisasi variabel
    $selectedBarang = null;
    $mutasi = collect();
    $stokAwal = 0;
    $stokAkhir = 0;
    $totalMasuk = 0;
    $totalKeluar = 0;
    $chartData = ['labels' => [], 'masuk' => [], 'keluar' => [], 'saldo' => []];
    
    // Data untuk semua barang
    $allBarangStats = collect();
    $totalNilaiPersediaan = 0;
    
    if ($barangId) {
        // Mode: Detail satu barang
        $selectedBarang = Barang::with(['kategori', 'satuan'])->find($barangId);
        
        if ($selectedBarang) {
            // Hitung stok awal (sebelum tanggal awal)
            $stokAwal = $this->getStokAwal($selectedBarang->id, $tanggalAwal);
            
            // Ambil data barang masuk
            $barangMasuk = BarangMasuk::where('barang_id', $selectedBarang->id)
                ->whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal_masuk', 'asc')
                ->get()
                ->map(function($item) {
                    return (object)[
                        'tanggal' => $item->tanggal_masuk,
                        'jenis' => 'masuk',
                        'jumlah' => $item->jumlah,
                        'nomor_dokumen' => $item->nomor_dokumen,
                        'keterangan' => $item->nama_supplier,
                        'saldo' => 0
                    ];
                });
            
            // Ambil data barang keluar (pengeluaran)
            $barangKeluar = PengeluaranBarang::where('barang_id', $selectedBarang->id)
                ->whereBetween('tanggal_keluar', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal_keluar', 'asc')
                ->get()
                ->map(function($item) {
                    return (object)[
                        'tanggal' => $item->tanggal_keluar,
                        'jenis' => 'keluar',
                        'jumlah' => $item->jumlah,
                        'nomor_surat' => $item->nomor_surat,
                        'keperluan' => $item->keperluan,
                        'saldo' => 0
                    ];
                });
            
            // Gabungkan dan urutkan berdasarkan tanggal
            $mutasi = $barangMasuk->concat($barangKeluar)
                ->sortBy('tanggal')
                ->values();
            
            // Hitung saldo berjalan dan total
            $saldo = $stokAwal;
            $groupedByDate = [];
            
            foreach ($mutasi as $item) {
                if ($item->jenis == 'masuk') {
                    $saldo += $item->jumlah;
                    $totalMasuk += $item->jumlah;
                } else {
                    $saldo -= $item->jumlah;
                    $totalKeluar += $item->jumlah;
                }
                $item->saldo = $saldo;
                
                // Group by date untuk chart
                $dateKey = Carbon::parse($item->tanggal)->format('Y-m-d');
                if (!isset($groupedByDate[$dateKey])) {
                    $groupedByDate[$dateKey] = [
                        'masuk' => 0,
                        'keluar' => 0,
                        'saldo' => $saldo
                    ];
                }
                
                if ($item->jenis == 'masuk') {
                    $groupedByDate[$dateKey]['masuk'] += $item->jumlah;
                } else {
                    $groupedByDate[$dateKey]['keluar'] += $item->jumlah;
                }
                $groupedByDate[$dateKey]['saldo'] = $saldo;
            }
            
            $stokAkhir = $saldo;
            
            // Siapkan data untuk chart
            $sortedDates = collect($groupedByDate)->keys()->sort();
            foreach ($sortedDates as $date) {
                $chartData['labels'][] = Carbon::parse($date)->format('d M');
                $chartData['masuk'][] = $groupedByDate[$date]['masuk'];
                $chartData['keluar'][] = $groupedByDate[$date]['keluar'];
                $chartData['saldo'][] = $groupedByDate[$date]['saldo'];
            }
        }
    } else {
        // Mode: Semua barang - Hitung statistik untuk setiap barang
        foreach ($barangList as $barang) {
            // Hitung stok awal (sebelum tanggal awal)
            $stokAwalBarang = $this->getStokAwal($barang->id, $tanggalAwal);
            
            // Hitung total masuk dalam periode
            $totalMasukBarang = BarangMasuk::where('barang_id', $barang->id)
                ->whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])
                ->sum('jumlah');
            
            // Hitung total keluar dalam periode
            $totalKeluarBarang = PengeluaranBarang::where('barang_id', $barang->id)
                ->whereBetween('tanggal_keluar', [$tanggalAwal, $tanggalAkhir])
                ->sum('jumlah');
            
            // Hitung stok akhir
            $stokAkhirBarang = $stokAwalBarang + $totalMasukBarang - $totalKeluarBarang;
            
            // Hitung nilai total (stok akhir * harga terbaru)
            $hargaTerbaru = BarangMasuk::where('barang_id', $barang->id)
                ->orderBy('tanggal_masuk', 'desc')
                ->value('harga_satuan') ?? 0;
            $nilaiTotal = $stokAkhirBarang * $hargaTerbaru;
            
            $totalNilaiPersediaan += $nilaiTotal;
            
            $allBarangStats->push([
                'id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori->name ?? '-',
                'satuan' => $barang->satuan->name ?? $barang->satuan_nama ?? 'pcs',
                'stok_awal' => $stokAwalBarang,
                'total_masuk' => $totalMasukBarang,
                'total_keluar' => $totalKeluarBarang,
                'stok_akhir' => $stokAkhirBarang,
                'nilai_total' => $nilaiTotal,
                'harga_terbaru' => $hargaTerbaru
            ]);
        }
        
        // Urutkan berdasarkan nama barang
        $allBarangStats = $allBarangStats->sortBy('nama_barang')->values();
    }
    
    return view('admin.kartu-persediaan', [
        'barangList' => $barangList,
        'selectedBarang' => $selectedBarang ?? null,
        'tanggalAwal' => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir,
        'allBarangStats' => $allBarangStats,
        'totalNilaiPersediaan' => $totalNilaiPersediaan,
        'mutasi' => $mutasi,
        'stokAwal' => $stokAwal,
        'stokAkhir' => $stokAkhir,
        'totalMasuk' => $totalMasuk,
        'totalKeluar' => $totalKeluar,
        'chartData' => $chartData,
    ]);
}

/**
 * Print kartu persediaan page details.
 */
public function kartuPersediaanPrint(Request $request)
{
    // Ambil daftar barang yang sudah memiliki barang masuk
    $barangList = Barang::with(['kategori', 'satuan'])
        ->has('barangMasuk')
        ->orderBy('nama_barang')
        ->get();
    
    $barangId = $request->get('barang_id');
    $periode = $request->get('periode', 'bulan_ini');
    $tanggalAwal = $request->get('tanggal_awal');
    $tanggalAkhir = $request->get('tanggal_akhir');
    
    // Set default tanggal berdasarkan periode
    $today = Carbon::now();
    if ($periode == 'custom') {
        $tanggalAwal = $tanggalAwal ? Carbon::parse($tanggalAwal) : $today->copy()->startOfMonth();
        $tanggalAkhir = $tanggalAkhir ? Carbon::parse($tanggalAkhir) : $today;
    } elseif ($periode == 'bulan_ini') {
        $tanggalAwal = $today->copy()->startOfMonth();
        $tanggalAkhir = $today->copy()->endOfMonth();
    } elseif ($periode == '3_bulan') {
        $tanggalAwal = $today->copy()->subMonths(2)->startOfMonth();
        $tanggalAkhir = $today;
    } elseif ($periode == '6_bulan') {
        $tanggalAwal = $today->copy()->subMonths(5)->startOfMonth();
        $tanggalAkhir = $today;
    } elseif ($periode == 'tahun_ini') {
        $tanggalAwal = $today->copy()->startOfYear();
        $tanggalAkhir = $today;
    } else {
        $tanggalAwal = $today->copy()->startOfMonth();
        $tanggalAkhir = $today->copy()->endOfMonth();
    }
    
    // Inisialisasi variabel
    $selectedBarang = null;
    $mutasi = collect();
    $stokAwal = 0;
    $stokAkhir = 0;
    $totalMasuk = 0;
    $totalKeluar = 0;
    
    // Data untuk semua barang
    $allBarangStats = collect();
    $totalNilaiPersediaan = 0;
    
    if ($barangId) {
        // Mode: Detail satu barang
        $selectedBarang = Barang::with(['kategori', 'satuan'])->find($barangId);
        
        if ($selectedBarang) {
            // Hitung stok awal (sebelum tanggal awal)
            $stokAwal = $this->getStokAwal($selectedBarang->id, $tanggalAwal);
            
            // Ambil data barang masuk
            $barangMasuk = BarangMasuk::where('barang_id', $selectedBarang->id)
                ->whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal_masuk', 'asc')
                ->get()
                ->map(function($item) {
                    return (object)[
                        'tanggal' => $item->tanggal_masuk,
                        'jenis' => 'masuk',
                        'jumlah' => $item->jumlah,
                        'nomor_dokumen' => $item->nomor_dokumen,
                        'keterangan' => $item->nama_supplier,
                        'saldo' => 0
                    ];
                });
            
            // Ambil data barang keluar (pengeluaran)
            $barangKeluar = PengeluaranBarang::where('barang_id', $selectedBarang->id)
                ->whereBetween('tanggal_keluar', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal_keluar', 'asc')
                ->get()
                ->map(function($item) {
                    return (object)[
                        'tanggal' => $item->tanggal_keluar,
                        'jenis' => 'keluar',
                        'jumlah' => $item->jumlah,
                        'nomor_surat' => $item->nomor_surat,
                        'keperluan' => $item->keperluan,
                        'saldo' => 0
                    ];
                });
            
            // Gabungkan dan urutkan berdasarkan tanggal
            $mutasi = $barangMasuk->concat($barangKeluar)
                ->sortBy('tanggal')
                ->values();
            
            // Hitung saldo berjalan dan total
            $saldo = $stokAwal;
            
            foreach ($mutasi as $item) {
                if ($item->jenis == 'masuk') {
                    $saldo += $item->jumlah;
                    $totalMasuk += $item->jumlah;
                } else {
                    $saldo -= $item->jumlah;
                    $totalKeluar += $item->jumlah;
                }
                $item->saldo = $saldo;
            }
            
            $stokAkhir = $saldo;
        }
    } else {
        // Mode: Semua barang - Hitung statistik untuk setiap barang
        foreach ($barangList as $barang) {
            // Hitung stok awal (sebelum tanggal awal)
            $stokAwalBarang = $this->getStokAwal($barang->id, $tanggalAwal);
            
            // Hitung total masuk dalam periode
            $totalMasukBarang = BarangMasuk::where('barang_id', $barang->id)
                ->whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])
                ->sum('jumlah');
            
            // Hitung total keluar dalam periode
            $totalKeluarBarang = PengeluaranBarang::where('barang_id', $barang->id)
                ->whereBetween('tanggal_keluar', [$tanggalAwal, $tanggalAkhir])
                ->sum('jumlah');
            
            // Hitung stok akhir
            $stokAkhirBarang = $stokAwalBarang + $totalMasukBarang - $totalKeluarBarang;
            
            // Hitung nilai total (stok akhir * harga terbaru)
            $hargaTerbaru = BarangMasuk::where('barang_id', $barang->id)
                ->orderBy('tanggal_masuk', 'desc')
                ->value('harga_satuan') ?? 0;
            $nilaiTotal = $stokAkhirBarang * $hargaTerbaru;
            
            $totalNilaiPersediaan += $nilaiTotal;
            
            $allBarangStats->push([
                'id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori->name ?? '-',
                'satuan' => $barang->satuan->name ?? $barang->satuan_nama ?? 'pcs',
                'stok_awal' => $stokAwalBarang,
                'total_masuk' => $totalMasukBarang,
                'total_keluar' => $totalKeluarBarang,
                'stok_akhir' => $stokAkhirBarang,
                'nilai_total' => $nilaiTotal,
                'harga_terbaru' => $hargaTerbaru
            ]);
        }
        
        // Urutkan berdasarkan nama barang
        $allBarangStats = $allBarangStats->sortBy('nama_barang')->values();
    }
    
    return view('exports.kartu-persediaan-print', [
        'selectedBarang' => $selectedBarang ?? null,
        'tanggalAwal' => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir,
        'allBarangStats' => $allBarangStats,
        'totalNilaiPersediaan' => $totalNilaiPersediaan,
        'mutasi' => $mutasi,
        'stokAwal' => $stokAwal,
        'stokAkhir' => $stokAkhir,
        'totalMasuk' => $totalMasuk,
        'totalKeluar' => $totalKeluar,
    ]);
}

/**
 * Get stok awal sebelum tanggal tertentu.
 */
private function getStokAwal($barangId, $tanggalAwal)
{
    $totalMasuk = BarangMasuk::where('barang_id', $barangId)
        ->where('tanggal_masuk', '<', $tanggalAwal)
        ->sum('jumlah');
    
    $totalKeluar = PengeluaranBarang::where('barang_id', $barangId)
        ->where('tanggal_keluar', '<', $tanggalAwal)
        ->sum('jumlah');
    
    return $totalMasuk - $totalKeluar;
}
    /**
     * Display barang management page.
     */
    public function manajemenBarang(Request $request)
    {
        // Ambil semua barang dengan relasi
        $query = Barang::with(['kategori', 'satuan']);
        
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('kode_barang', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$request->search}%");
            });
        }
        
        $barangList = $query->orderBy('nama_barang', 'asc')
            ->paginate(10)
            ->withQueryString();
        
        // Hitung stok untuk setiap barang
        foreach ($barangList as $barang) {
            $totalMasuk = BarangMasuk::where('barang_id', $barang->id)->sum('jumlah');
            $totalKeluar = PengeluaranBarang::where('barang_id', $barang->id)->sum('jumlah');
            $barang->stok_akhir = $totalMasuk - $totalKeluar;
        }
        
        // Hitung statistik
        $totalBarang = Barang::count();
        $totalKategori = \App\Models\Kategori::count();
        
        // Hitung stok menipis dan stok habis
        $stokMenipis = 0;
        $stokHabis = 0;
        
        $allBarang = Barang::all();
        foreach ($allBarang as $barang) {
            $totalMasuk = BarangMasuk::where('barang_id', $barang->id)->sum('jumlah');
            $totalKeluar = PengeluaranBarang::where('barang_id', $barang->id)->sum('jumlah');
            $stok = $totalMasuk - $totalKeluar;
            $stokMinimal = $barang->stok_minimal ?? 0;
            
            if ($stok <= 0) {
                $stokHabis++;
            } elseif ($stok <= $stokMinimal) {
                $stokMenipis++;
            }
        }
        
        return view('admin.manajemen-barang', compact(
            'barangList',
            'totalBarang',
            'totalKategori',
            'stokMenipis',
            'stokHabis'
        ));
    }
    
    /**
     * Download CSV for Barang List
     */
    public function exportBarangCsv()
    {
        $barangs = Barang::with(['kategori', 'satuan'])->orderBy('nama_barang', 'asc')->get();
        
        $filename = "daftar_barang_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen('php://output', 'w');
        
        // Output headers so that the file is downloaded rather than displayed
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        // BOM for Excel UTF-8 compatibility
        fputs($handle, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

        // Add CSV headers
        fputcsv($handle, [
            'Kode Barang', 
            'Nama Barang', 
            'Kategori', 
            'Satuan', 
            'Stok Minimal', 
            'Harga Satuan', 
            'Total Masuk', 
            'Total Keluar', 
            'Sisa Stok Saat Ini'
        ], ';');

        // Add data
        foreach($barangs as $barang) {
            $totalMasuk = BarangMasuk::where('barang_id', $barang->id)->sum('jumlah');
            $totalKeluar = PengeluaranBarang::where('barang_id', $barang->id)->sum('jumlah');
            $stok = $totalMasuk - $totalKeluar;
            
            fputcsv($handle, [
                $barang->kode_barang,
                $barang->nama_barang,
                $barang->kategori->name ?? '-',
                $barang->satuan->name ?? 'pcs',
                $barang->stok_minimal ?? 0,
                $barang->harga ?? 0,
                $totalMasuk,
                $totalKeluar,
                $stok
            ], ';');
        }
        
        fclose($handle);
        exit();
    }
    
    public function templateBarangCsv()
    {
        $filename = "Template_Import_Barang.csv";
        $handle = fopen('php://output', 'w');
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        fputs($handle, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

        // Define Headers
        fputcsv($handle, [
            'Kode Barang', 
            'Nama Barang', 
            'Spesifikasi/Merk/Tipe', 
            'Satuan',
            'Sisa Stok Awal',
            'Harga Satuan',
            'Kategori (Boleh Kosong)'
        ], ';');
        
        // Add sample row
        fputcsv($handle, [
            '1.3.2.05.01..', 'Lap Top', 'Lenovo IP 315', 'Unit', '4', '7200000', 'LAIN ALAT PERAGA'
        ], ';');

        fclose($handle);
        exit();
    }
    
    public function importBarangCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), "r");
        
        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== b"\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 1000, ";");
        if(!$header) {
             return redirect()->back()->with('error', 'Format CSV tidak valid!');
        }

        $count = 0;
        
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Adjust if array length doesn't match
                if(count($row) < 6) continue;
                
                $kode = trim($row[0]);
                $nama = trim($row[1]);
                $spesifikasi = trim($row[2]);
                $satuan_name = trim($row[3] ?? 'Unit');
                $stok_awal = (int)str_replace(['.', ','], '', trim($row[4] ?? '0'));
                $harga = (float)str_replace(['.', ','], '', trim($row[5] ?? '0'));
                $kategori_name = trim($row[6] ?? 'Umum');
                
                if(empty($kode) || empty($nama)) continue;

                // Find or Create Kategori
                $kategori = \App\Models\Kategori::firstOrCreate(
                    ['name' => $kategori_name],
                    [
                        'name' => $kategori_name, 
                        'code' => strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $kategori_name), 0, 4)) . rand(10,99),
                        'created_at' => now()
                    ]
                );
                
                // Find or Create Satuan
                $satuan = \App\Models\Satuan::firstOrCreate(
                    ['name' => $satuan_name],
                    [
                        'name' => $satuan_name, 
                        'code' => strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $satuan_name), 0, 3)) . rand(10,99),
                        'created_at' => now()
                    ]
                );

                // Insert or Update Barang
                $barang = Barang::updateOrCreate(
                    ['kode_barang' => $kode],
                    [
                        'nama_barang' => $nama,
                        'description' => $spesifikasi,
                        'kategori_id' => $kategori->id,
                        'satuan_id' => $satuan->id,
                        'harga_satuan' => $harga
                    ]
                );
                
                // If Stok Awal > 0, inject to BarangMasuk to adjust inventory stock to proper balance
                if ($stok_awal > 0 && $barang->wasRecentlyCreated) {
                    \App\Models\BarangMasuk::create([
                        'tanggal_masuk' => now()->format('Y-m-d'),
                        'nomor_dokumen' => 'STK-AWAL-' . date('YmdHis') . rand(10,99),
                        'nama_supplier' => 'Sistem Import ' . date('d-m-Y'),
                        'barang_id' => $barang->id,
                        'kode_barang' => $kode,
                        'nama_barang' => $nama,
                        'satuan_nama' => $satuan_name,
                        'nusp' => '-',
                        'spesifikasi_nama_barang' => $spesifikasi,
                        'jumlah' => $stok_awal,
                        'harga_satuan' => $harga,
                        'nilai_total' => $harga * $stok_awal,
                        'keterangan' => 'Stok awal dari import CSV',
                        'created_by' => \Illuminate\Support\Facades\Auth::id() ?? 1
                    ]);
                }
                $count++;
            }
            DB::commit();
            fclose($handle);
            return redirect()->back()->with('success', "Berhasil import $count data barang baru!");
        } catch (\Exception $e) {
            DB::rollback();
            fclose($handle);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
    /**
 * Get permintaan details for approval modal (JSON)
 */
public function getPermintaanDetailJson($id)
{
    try {
        $permintaan = PermintaanBarang::with(['user', 'details'])->find($id);
        
        if (!$permintaan) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'permintaan' => $permintaan
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Process approval request
 */
public function approvePermintaan(Request $request, $id)
{
    try {
        DB::beginTransaction();
        
        $permintaan = PermintaanBarang::findOrFail($id);
        
        // Update status permintaan utama
        $permintaan->status = 'disetujui_admin';
        $permintaan->approved_by = Auth::id();
        $permintaan->approved_at = now();
        $permintaan->save();
        
        // Update details
        if ($request->has('details')) {
            foreach ($request->details as $detailId => $data) {
                $detail = \App\Models\PermintaanDetail::find($detailId);
                if ($detail) {
                    $detail->disetujui_jumlah = $data['disetujui_jumlah'] ?? 0;
                    $detail->status = $data['status'] ?? 'disesuaikan';
                    $detail->catatan_admin = $data['catatan_admin'] ?? null;
                    $detail->save();
                    
                    // Jika disetujui, kurangi stok barang
                    if ($detail->status == 'disetujui' && $detail->disetujui_jumlah > 0) {
                        // Kurangi stok (implement sesuai logika Anda)
                        // $this->kurangiStok($detail->barang_id, $detail->disetujui_jumlah);
                    }
                }
            }
        }
        
        DB::commit();
        
        return redirect()->route('admin.permintaan.menunggu')
            ->with('success', 'Permintaan berhasil disetujui');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
    }
}

/**
 * Reject permintaan
 */
public function rejectPermintaan(Request $request, $id)
{
    try {
        $permintaan = PermintaanBarang::findOrFail($id);
        
        $permintaan->status = 'ditolak_admin';
        $permintaan->alasan_penolakan = $request->alasan;
        $permintaan->rejected_by = Auth::id();
        $permintaan->rejected_at = now();
        $permintaan->save();
        
        return redirect()->route('admin.permintaan.menunggu')
            ->with('success', 'Permintaan berhasil ditolak');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menolak permintaan: ' . $e->getMessage());
    }
}
    /**
     * Display divisi page.
     */
    public function divisiIndex()
    {
        return view('admin.divisi.index');
    }
    
    /**
     * Display dokumen SPB page.
     */
    public function dokumenSPB()
    {
        return view('admin.dokumen.spb');
    }
    
    /**
     * Display dokumen BAST page.
     */
    public function dokumenBAST()
    {
        return view('admin.dokumen.bast');
    }
    
    /**
     * Display dokumen SPPB page.
     */
    public function dokumenSPPB()
    {
        return view('admin.dokumen.sppb');
    }
    
    /**
     * Display laporan page.
     */
    public function laporanIndex(Request $request)
    {
        $jenis_laporan = $request->input('jenis_laporan', 'stok');
        $start_date = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $export = $request->input('export', false);

        $queryStart = Carbon::parse($start_date)->startOfDay();
        $queryEnd = Carbon::parse($end_date)->endOfDay();

        $data = collect();

        if ($jenis_laporan == 'stok') {
            $data = Barang::with(['kategori', 'satuan'])->get()->map(function($item) use ($queryStart, $queryEnd) {
                // To simplify, we get absolute stock. Time-based stock logic requires ledger
                $masuk = BarangMasuk::where('barang_id', $item->id)->whereBetween('tanggal_masuk', [$queryStart, $queryEnd])->sum('jumlah');
                $keluar = PengeluaranBarang::where('barang_id', $item->id)->whereBetween('tanggal_keluar', [$queryStart, $queryEnd])->sum('jumlah');
                
                $totalMasuk = BarangMasuk::where('barang_id', $item->id)->sum('jumlah');
                $totalKeluar = PengeluaranBarang::where('barang_id', $item->id)->sum('jumlah');
                $stokAkhir = $totalMasuk - $totalKeluar;
                
                // Estimate absolute stok awal relative to period
                $stokAwal = $stokAkhir - $masuk + $keluar; 
                
                return (object)[
                    'kode' => $item->kode_barang,
                    'nama' => $item->nama_barang,
                    'kategori' => $item->kategori->name ?? '-',
                    'satuan' => $item->satuan->name ?? 'pcs',
                    'stok_awal' => $stokAwal < 0 ? 0 : $stokAwal,
                    'masuk' => $masuk,
                    'keluar' => $keluar,
                    'stok_akhir' => $stokAkhir
                ];
            });
            
            if ($export == 'excel') {
                return $this->exportCsvLaporan('Laporan_Stok', ['Kode', 'Nama Barang', 'Kategori', 'Satuan', 'Stok Awal', 'Masuk', 'Keluar', 'Stok Akhir'], $data, ['kode', 'nama', 'kategori', 'satuan', 'stok_awal', 'masuk', 'keluar', 'stok_akhir']);
            }
            
        } elseif ($jenis_laporan == 'permintaan') {
            $data = PermintaanBarang::with(['user', 'details'])
                ->whereBetween('created_at', [$queryStart, $queryEnd])
                ->orderBy('created_at', 'desc')
                ->get();
                
            if ($export == 'excel') {
                $exportData = collect();
                foreach($data as $req) {
                    $barangs = $req->details->map(function($d) { return $d->nama_barang . ' ('.$d->disetujui_jumlah.')'; })->implode(', ');
                    $exportData->push((object)[
                        'tanggal' => $req->created_at->format('Y-m-d'),
                        'nomor' => $req->nomor_surat,
                        'pemohon' => $req->user->nama ?? '-',
                        'divisi' => $req->divisi,
                        'barang' => $barangs,
                        'status' => $req->status,
                    ]);
                }
                return $this->exportCsvLaporan('Laporan_Permintaan', ['Tanggal', 'No Surat', 'Pemohon', 'Divisi', 'Daftar Barang', 'Status'], $exportData, ['tanggal', 'nomor', 'pemohon', 'divisi', 'barang', 'status']);
            }
        }
        
        return view('admin.laporan', compact('jenis_laporan', 'start_date', 'end_date', 'data'));
    }
    
    private function exportCsvLaporan($filename_prefix, $headers, $data, $columns)
    {
        $filename = $filename_prefix . "_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        fputs($handle, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($handle, $headers, ';');

        foreach($data as $row) {
            $csvRow = [];
            foreach($columns as $col) {
                $csvRow[] = $row->$col;
            }
            fputcsv($handle, $csvRow, ';');
        }
        
        fclose($handle);
        exit();
    }
    
    /**
     * Display settings page.
     */
    public function settings()
    {
        $settings = \App\Models\Setting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }
    
    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'settings' => 'required|array'
        ]);
        
        foreach ($request->settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}