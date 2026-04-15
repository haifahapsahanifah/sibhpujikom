<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'satuan', 'createdBy']);
        
        // Filter berdasarkan rentang tanggal
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }
        
        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_dokumen', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_supplier', 'LIKE', "%{$request->search}%")
                  ->orWhere('kode_barang', 'LIKE', "%{$request->search}%");
            });
        }
        
        $barangMasuks = $query->orderBy('tanggal_masuk', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);
        
        // Simpan parameter filter untuk ditampilkan di view
        $filters = $request->only(['tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun', 'search']);
        
        $totalBarangMasuk = BarangMasuk::count();
        $totalNilai = BarangMasuk::sum('nilai_total');
        $totalJumlah = BarangMasuk::sum('jumlah');
        
        $barangs = Barang::orderBy('nama_barang')->get();
        $satuans = Satuan::orderBy('name')->get();
        
        return view('admin.barang-masuk', compact(
            'barangMasuks', 
            'totalBarangMasuk', 
            'totalNilai', 
            'totalJumlah',
            'barangs',
            'satuans',
            'filters'
        ));
    }
    
    public function store(Request $request)
    {
        // Validasi dengan semua field wajib diisi kecuali keterangan
        $validator = Validator::make($request->all(), [
            'tanggal_masuk' => 'required|date',
            'nomor_dokumen' => 'required|string|max:100',
            'nama_supplier' => 'required|string|max:200',
            'barang_id' => 'required|exists:barang,id',
            'nusp' => 'required|string|max:100',
            'spesifikasi_nama_barang' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
            'nomor_dokumen.required' => 'Nomor dokumen wajib diisi',
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'barang_id.required' => 'Barang wajib dipilih',
            'barang_id.exists' => 'Barang tidak valid',
            'nusp.required' => 'NUSP wajib diisi',
            'spesifikasi_nama_barang.required' => 'Spesifikasi nama barang wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif'
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $barang = Barang::findOrFail($request->barang_id);
            
            $hargaSatuan = $request->harga_satuan > 0 ? $request->harga_satuan : $barang->harga_satuan;
            $nilaiTotal = $request->jumlah * $hargaSatuan;
            
            $barangMasuk = BarangMasuk::create([
                'tanggal_masuk' => $request->tanggal_masuk,
                'nomor_dokumen' => $request->nomor_dokumen,
                'nama_supplier' => $request->nama_supplier,
                'barang_id' => $request->barang_id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'nusp' => $request->nusp,
                'spesifikasi_nama_barang' => $request->spesifikasi_nama_barang,
                'jumlah' => $request->jumlah,
                'satuan_id' => $barang->satuan_id,
                'satuan_nama' => $barang->satuan->name ?? '-',
                'harga_satuan' => $hargaSatuan,
                'nilai_total' => $nilaiTotal,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id()
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Barang masuk berhasil ditambahkan',
                    'data' => $barangMasuk->load(['barang', 'satuan', 'createdBy'])
                ]);
            }
            
            return redirect()->route('admin.barang-masuk.index')
                ->with('success', 'Barang masuk berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function show($id)
    {
        try {
            $barangMasuk = BarangMasuk::with(['barang', 'satuan', 'createdBy'])->findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $barangMasuk
                ]);
            }
            
            return view('admin.barang-masuk.show', compact('barangMasuk'));
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            return redirect()->route('admin.barang-masuk.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }
    
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $barangMasuk = BarangMasuk::findOrFail($id);
            $barangMasuk->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Barang masuk berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function print(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'satuan', 'createdBy']);
        
        // Filter berdasarkan rentang tanggal
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_awal);
            $tanggalAwal = $request->tanggal_awal;
        } else {
            $tanggalAwal = null;
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
            $tanggalAkhir = $request->tanggal_akhir;
        } else {
            $tanggalAkhir = null;
        }
        
        // Filter berdasarkan bulan dan tahun
        $bulan = $request->bulan ?? null;
        $tahun = $request->tahun ?? null;
        
        if ($bulan) {
            $query->whereMonth('tanggal_masuk', $bulan);
        }
        
        if ($tahun) {
            $query->whereYear('tanggal_masuk', $tahun);
        }
        
        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_dokumen', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_supplier', 'LIKE', "%{$request->search}%")
                  ->orWhere('kode_barang', 'LIKE', "%{$request->search}%");
            });
        }
        
        $barangMasuks = $query->orderBy('tanggal_masuk', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        $totalTransaksi = $barangMasuks->count();
        $totalJumlah = $barangMasuks->sum('jumlah');
        $totalNilai = $barangMasuks->sum('nilai_total');
        
        return view('exports.barang-masuk-print', compact(
            'barangMasuks',
            'totalTransaksi',
            'totalJumlah',
            'totalNilai',
            'tanggalAwal',
            'tanggalAkhir',
            'bulan',
            'tahun'
        ));
    }
}