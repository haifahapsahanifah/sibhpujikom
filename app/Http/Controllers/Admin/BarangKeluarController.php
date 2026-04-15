<?php
// app/Http/Controllers/Admin/BarangKeluarController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengeluaranBarang;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = PengeluaranBarang::with(['createdBy', 'permintaan']);
        
        // Filter tanggal
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal_keluar', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_keluar', '<=', $request->tanggal_akhir);
        }
        
        // Filter divisi
        if ($request->has('divisi') && $request->divisi) {
            $query->where('divisi', $request->divisi);
        }
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_surat', 'LIKE', "%{$request->search}%")
                  ->orWhere('kode_barang', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$request->search}%")
                  ->orWhere('penerima', 'LIKE', "%{$request->search}%");
            });
        }
        
        $pengeluaran = $query->orderBy('tanggal_keluar', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);
        
        $stats = [
            'total' => PengeluaranBarang::count(),
            'bulan_ini' => PengeluaranBarang::whereMonth('tanggal_keluar', date('m'))
                                           ->whereYear('tanggal_keluar', date('Y'))
                                           ->count(),
            'total_jumlah' => PengeluaranBarang::sum('jumlah'),
        ];
        
        return view('admin.barang-keluar.index', compact('pengeluaran', 'stats'));
    }
    
    /**
     * Get detail data for AJAX request
     */
    public function show($id)
    {
        try {
            $pengeluaran = PengeluaranBarang::with(['createdBy', 'permintaan'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pengeluaran->id,
                    'nomor_surat' => $pengeluaran->nomor_surat,
                    'tanggal_keluar' => $pengeluaran->tanggal_keluar,
                    'kode_barang' => $pengeluaran->kode_barang,
                    'nama_barang' => $pengeluaran->nama_barang,
                    'jumlah' => $pengeluaran->jumlah,
                    'satuan' => $pengeluaran->satuan,
                    'penerima' => $pengeluaran->penerima,
                    'divisi' => $pengeluaran->divisi,
                    'keperluan' => $pengeluaran->keperluan,
                    'keterangan' => $pengeluaran->keterangan,
                    'created_at' => $pengeluaran->created_at,
                    'created_by' => $pengeluaran->createdBy ? [
                        'nama' => $pengeluaran->createdBy->nama ?? $pengeluaran->createdBy->name
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }
    
    public function create()
    {
        $permintaanDisetujui = PermintaanBarang::with(['user', 'details'])
            ->where('status', 'disetujui')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.barang-keluar.create', compact('permintaanDisetujui'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_barang,id',
            'tanggal_keluar' => 'required|date',
            'penerima' => 'required|string|max:200',
            'keterangan' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $permintaan = PermintaanBarang::with('details')->findOrFail($request->permintaan_id);
            
            // Buat record pengeluaran barang
            foreach ($permintaan->details as $detail) {
                if ($detail->disetujui_jumlah > 0) {
                    PengeluaranBarang::create([
                        'permintaan_id' => $permintaan->id,
                        'nomor_surat' => $permintaan->nomor_surat,
                        'tanggal_keluar' => $request->tanggal_keluar,
                        'kode_barang' => $detail->kode_barang,
                        'nama_barang' => $detail->nama_barang,
                        'jumlah' => $detail->disetujui_jumlah,
                        'satuan' => $detail->satuan,
                        'penerima' => $request->penerima,
                        'divisi' => $permintaan->divisi,
                        'keperluan' => $detail->keperluan,
                        'keterangan' => $request->keterangan,
                        'created_by' => Auth::id()
                    ]);
                }
            }
            
            // Update status permintaan menjadi selesai
            $permintaan->update([
                'status' => 'selesai'
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.barang-keluar.index')
                ->with('success', 'Barang keluar berhasil dicatat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat barang keluar: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $pengeluaran = PengeluaranBarang::findOrFail($id);
            $pengeluaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Print function for barang keluar
     */
   /**
 * Print function for barang keluar
 */
public function print(Request $request)
{
    $query = PengeluaranBarang::with(['createdBy']);
    
    if ($request->has('tanggal_awal') && $request->tanggal_awal) {
        $query->whereDate('tanggal_keluar', '>=', $request->tanggal_awal);
    }
    
    if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
        $query->whereDate('tanggal_keluar', '<=', $request->tanggal_akhir);
    }
    
    if ($request->has('divisi') && $request->divisi) {
        $query->where('divisi', $request->divisi);
    }
    
    if ($request->has('search') && $request->search) {
        $query->where(function($q) use ($request) {
            $q->where('nomor_surat', 'LIKE', "%{$request->search}%")
              ->orWhere('kode_barang', 'LIKE', "%{$request->search}%")
              ->orWhere('nama_barang', 'LIKE', "%{$request->search}%")
              ->orWhere('penerima', 'LIKE', "%{$request->search}%");
        });
    }
    
    $pengeluaran = $query->orderBy('tanggal_keluar', 'desc')->get();
    
    // Hitung statistik untuk print
    $totalTransaksi = $pengeluaran->count();
    $totalJumlah = $pengeluaran->sum('jumlah');
    $totalDivisi = $pengeluaran->groupBy('divisi')->count();
    
    // Data untuk filter di header
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $divisi = $request->divisi;
    
    return view('exports.barang-keluar-print', compact(
        'pengeluaran', 
        'totalTransaksi', 
        'totalJumlah', 
        'totalDivisi',
        'tanggal_awal',
        'tanggal_akhir',
        'divisi'
    ));
}
    
    public function export(Request $request)
    {
        $query = PengeluaranBarang::with(['createdBy']);
        
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal_keluar', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_keluar', '<=', $request->tanggal_akhir);
        }
        
        if ($request->has('divisi') && $request->divisi) {
            $query->where('divisi', $request->divisi);
        }
        
        $pengeluaran = $query->orderBy('tanggal_keluar', 'desc')->get();
        
        if ($request->format === 'excel') {
            // Return Excel
            return response()->json(['message' => 'Excel export coming soon']);
        } else {
            // Return PDF
            return response()->json(['message' => 'PDF export coming soon']);
        }
    }
    
    public function report()
    {
        $stats = [
            'total_per_bulan' => PengeluaranBarang::selectRaw('DATE_FORMAT(tanggal_keluar, "%Y-%m") as bulan, COUNT(*) as total, SUM(jumlah) as total_jumlah')
                ->groupBy('bulan')
                ->orderBy('bulan', 'desc')
                ->get(),
            'total_per_divisi' => PengeluaranBarang::selectRaw('divisi, COUNT(*) as total, SUM(jumlah) as total_jumlah')
                ->groupBy('divisi')
                ->orderBy('total', 'desc')
                ->get(),
            'total_per_barang' => PengeluaranBarang::selectRaw('nama_barang, kode_barang, COUNT(*) as total_transaksi, SUM(jumlah) as total_jumlah')
                ->groupBy('nama_barang', 'kode_barang')
                ->orderBy('total_jumlah', 'desc')
                ->limit(10)
                ->get()
        ];
        
        return view('admin.barang-keluar.report', compact('stats'));
    }
}