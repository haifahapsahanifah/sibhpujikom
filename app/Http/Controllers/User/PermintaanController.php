<?php
// app/Http/Controllers/User/PermintaanController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\DetailPermintaanBarang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermintaanController extends Controller
{
    /**
     * Display a listing of the resource (Halaman Riwayat).
     */
    public function index(Request $request)
    {
        $query = PermintaanBarang::with(['user', 'details'])
            ->where('user_id', Auth::id());
            
        if ($request->has('search') && $request->search) {
            $query->where('nomor_surat', 'LIKE', "%{$request->search}%");
        }
            
        $permintaans = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $query = PermintaanBarang::where('user_id', Auth::id());
        
        $stats = [
            'total' => (clone $query)->count(),
            'menunggu_admin' => (clone $query)->where('status', 'menunggu_admin')->count(),
            'menunggu_user' => (clone $query)->where('status', 'menunggu_user')->count(),
            'disetujui' => (clone $query)->where('status', 'disetujui')->count(),
            'ditolak' => (clone $query)->where('status', 'ditolak')->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];

        return view('user.permintaan.riwayat', compact('permintaans', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangMasukRaw = BarangMasuk::with('barang.satuan')
            ->select('barang_id', 'kode_barang', 'nama_barang', 'satuan_nama', 'satuan_id')
            ->groupBy('barang_id', 'kode_barang', 'nama_barang', 'satuan_nama', 'satuan_id')
            ->orderBy('nama_barang')
            ->get();
            
        // Calculate raw stocks
        $barangMasuk = [];
        foreach ($barangMasukRaw as $item) {
            // Fallback for empty satuan_nama
            if (empty($item->satuan_nama) && $item->barang && $item->barang->satuan) {
                $item->satuan_nama = $item->barang->satuan->name ?? 'pcs';
            } elseif (empty($item->satuan_nama)) {
                $item->satuan_nama = 'pcs'; // ultimate fallback
            }

            $totalMasuk = BarangMasuk::where('barang_id', $item->barang_id)->sum('jumlah');
            $totalKeluar = \App\Models\PengeluaranBarang::where('barang_id', $item->barang_id)->sum('jumlah');
            $sisaStok = $totalMasuk - $totalKeluar;
            
            // Allow them to request only if stock > 0.
            if ($sisaStok > 0) {
                $item->sisa_stok = $sisaStok;
                $barangMasuk[] = $item;
            }
        }
        
        return view('user.permintaan.create', compact('barangMasuk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log untuk debugging
        Log::info('Permintaan store request:', $request->all());
        
        // Validasi
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'divisi' => 'required|string',
            'tanggal_dibutuhkan' => 'required|date',
            'barang' => 'required|array|min:1',
            'barang.*.barang_id' => 'required',
            'barang.*.pengajuan_jumlah' => 'required|integer|min:1',
            'barang.*.keperluan' => 'required|string|min:3',
            'persetujuan_pengaju' => 'required|accepted',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Generate nomor surat
            $nomorSurat = PermintaanBarang::generateNomorSurat();
            
            // Create permintaan
            $permintaan = PermintaanBarang::create([
                'nomor_surat' => $nomorSurat,
                'user_id' => Auth::id(),
                'divisi' => $request->divisi,
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
                'prioritas' => 'biasa',
                'status' => 'menunggu_admin',
                'catatan' => $request->catatan,
            ]);

            // Create details
            foreach ($request->barang as $item) {
                $barangMasuk = BarangMasuk::where('barang_id', $item['barang_id'])->first();
                
                if ($barangMasuk) {
                    DetailPermintaanBarang::create([
                        'permintaan_barang_id' => $permintaan->id,
                        'barang_id' => $item['barang_id'],
                        'kode_barang' => $barangMasuk->kode_barang,
                        'nama_barang' => $barangMasuk->nama_barang,
                        'spesifikasi' => $barangMasuk->spesifikasi_nama_barang ?? null,
                        'pengajuan_jumlah' => $item['pengajuan_jumlah'],
                        'satuan' => $barangMasuk->satuan_nama,
                        'keperluan' => $item['keperluan'],
                        'status' => 'menunggu',
                    ]);
                }
            }

            DB::commit();
            
            Log::info('Permintaan created successfully:', ['id' => $permintaan->id]);

            // Notify Admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PermintaanBaruNotification($permintaan));

            // Kembalikan response sesuai request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Surat permintaan berhasil dikirim',
                    'redirect_url' => route('user.permintaan.riwayat')
                ]);
            }

            return redirect()->route('user.permintaan.riwayat')
                ->with('success', 'Permintaan barang berhasil diajukan. Menunggu persetujuan admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing permintaan:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan permintaan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyimpan permintaan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permintaan = PermintaanBarang::with(['user', 'details', 'admin'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('user.permintaan.detail', compact('permintaan'));
    }

    /**
     * Get detail permintaan as JSON for modal
     */
    public function getDetailJson($id)
    {
        try {
            $permintaan = PermintaanBarang::with(['user', 'details', 'admin'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'permintaan' => [
                    'id' => $permintaan->id,
                    'nomor_surat' => $permintaan->nomor_surat,
                    'divisi' => $permintaan->divisi,
                    'tanggal_dibutuhkan' => $permintaan->tanggal_dibutuhkan->format('Y-m-d'),
                    'status' => $permintaan->status,
                    'catatan' => $permintaan->catatan,
                    'alasan_ditolak' => $permintaan->alasan_ditolak,
                    'disetujui_admin_at' => $permintaan->disetujui_admin_at ? $permintaan->disetujui_admin_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $permintaan->created_at->format('Y-m-d H:i:s'),
                    'user' => [
                        'nama' => $permintaan->user->nama,
                    ],
                    'admin' => $permintaan->admin ? [
                        'nama' => $permintaan->admin->nama,
                    ] : null,
                    'details' => $permintaan->details->map(function($detail) {
                        return [
                            'id' => $detail->id,
                            'kode_barang' => $detail->kode_barang,
                            'nama_barang' => $detail->nama_barang,
                            'spesifikasi' => $detail->spesifikasi,
                            'pengajuan_jumlah' => $detail->pengajuan_jumlah,
                            'disetujui_jumlah' => $detail->disetujui_jumlah,
                            'satuan' => $detail->satuan,
                            'keperluan' => $detail->keperluan,
                            'status' => $detail->status,
                            'catatan_admin' => $detail->catatan_admin,
                        ];
                    }),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Approve permintaan by user
     */
    public function approveUser(Request $request, $id)
    {
        try {
            $permintaan = PermintaanBarang::where('user_id', Auth::id())
                ->where('status', 'menunggu_user')
                ->findOrFail($id);

            DB::beginTransaction();
            
            $permintaan->update([
                'status' => 'disetujui',
                'disetujui_user_at' => now(),
            ]);

            foreach ($permintaan->details as $detail) {
                if ($detail->disetujui_jumlah && $detail->disetujui_jumlah > 0) {
                    \App\Models\PengeluaranBarang::create([
                        'permintaan_barang_id' => $permintaan->id,
                        'detail_permintaan_id' => $detail->id,
                        'barang_id' => $detail->barang_id,
                        'kode_barang' => $detail->kode_barang,
                        'nama_barang' => $detail->nama_barang,
                        'jumlah' => $detail->disetujui_jumlah,
                        'satuan' => $detail->disetujui_satuan ?? $detail->satuan,
                        'penerima' => Auth::user()->nama,
                        'divisi' => $permintaan->divisi,
                        'tanggal_keluar' => now(),
                        'keperluan' => $detail->keperluan,
                        'nomor_surat' => $permintaan->nomor_surat,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan telah disetujui'
                ]);
            }

            return redirect()->route('user.permintaan.riwayat')
                ->with('success', 'Permintaan telah disetujui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyetujui: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function cetakStruk($id)
    {
        $permintaan = PermintaanBarang::with(['user', 'details', 'admin'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        if ($permintaan->status != 'selesai') {
            return redirect()->route('user.permintaan.riwayat')->with('error', 'Bukti pengambilan hanya tersedia untuk permintaan yang sudah selesai.');
        }

        $isAdminMode = false;
        return view('admin.permintaan.struk', compact('permintaan', 'isAdminMode'));
    }
}