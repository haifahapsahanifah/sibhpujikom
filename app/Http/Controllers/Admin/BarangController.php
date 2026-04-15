<?php
// app/Http/Controllers/Admin/BarangController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar semua barang
     */
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan']);
        
        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('kode_barang', 'LIKE', "%{$request->search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$request->search}%");
            });
        }
        
        // Filter kategori
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        $barangs = $query->orderBy('created_at', 'desc')->paginate(10);
        $totalBarang = Barang::count();
        
        // Untuk filter dropdown
        $kategoris = Kategori::orderBy('name')->get();
        $satuans = Satuan::orderBy('name')->get();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $barangs
            ]);
        }
        
        return view('admin.barang.index', compact('barangs', 'totalBarang', 'kategoris', 'satuans'));
    }

    /**
     * Menyimpan barang baru ke database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang',
            'nama_barang' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_satuan' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'satuan_id.required' => 'Satuan wajib dipilih',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $barang = Barang::create([
                'kode_barang' => strtoupper($request->kode_barang),
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
                'harga_satuan' => $request->harga_satuan,
                'description' => $request->description
            ]);
            
            DB::commit();

            // Load relasi untuk response
            $barang->load(['kategori', 'satuan']);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail satu barang
     */
    public function show($id)
    {
        try {
            $barang = Barang::with(['kategori', 'satuan'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Memperbarui data barang
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $id,
            'nama_barang' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_satuan' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'satuan_id.required' => 'Satuan wajib dipilih',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $barang = Barang::findOrFail($id);
            $barang->update([
                'kode_barang' => strtoupper($request->kode_barang),
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
                'harga_satuan' => $request->harga_satuan,
                'description' => $request->description
            ]);
            
            DB::commit();
            
            // Load relasi untuk response
            $barang->load(['kategori', 'satuan']);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diperbarui',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus barang dari database
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $barang = Barang::findOrFail($id);
            $barang->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data untuk edit
     */
    public function edit($id)
    {
        try {
            $barang = Barang::with(['kategori', 'satuan'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }
    
    /**
     * Cek kode barang (untuk validasi realtime)
     */
    public function checkKode(Request $request)
    {
        $kode = $request->kode_barang;
        $id = $request->id;
        
        $query = Barang::where('kode_barang', $kode);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'success' => true,
            'available' => !$exists
        ]);
    }
}