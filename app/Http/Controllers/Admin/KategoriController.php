<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori
     */
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->orderBy('created_at', 'desc')->get();
        $totalKategori = Kategori::count();
        
        return view('admin.kategori.index', compact('kategoris', 'totalKategori'));
    }

    /**
     * Menyimpan kategori baru ke database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:kategori,name',
            'code' => 'required|string|max:50|unique:kategori,code',
            'description' => 'nullable|string|max:255'
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Nama kategori sudah digunakan',
            'code.required' => 'Kode kategori wajib diisi',
            'code.unique' => 'Kode kategori sudah digunakan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategori = Kategori::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail satu kategori
     */
    public function show($id)
    {
        try {
            $kategori = Kategori::withCount('barangs')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Memperbarui data kategori
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:kategori,name,' . $id,
            'code' => 'required|string|max:50|unique:kategori,code,' . $id,
            'description' => 'nullable|string|max:255'
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Nama kategori sudah digunakan',
            'code.required' => 'Kode kategori wajib diisi',
            'code.unique' => 'Kode kategori sudah digunakan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus kategori dari database dengan pengecekan relasi
     */
    public function destroy($id)
    {
        try {
            $kategori = Kategori::withCount('barangs')->findOrFail($id);
            
            // Cek apakah kategori memiliki barang terkait
            if ($kategori->barangs_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Kategori '{$kategori->name}' tidak dapat dihapus karena masih memiliki {$kategori->barangs_count} barang terkait. Silahkan hapus atau pindahkan barang terlebih dahulu.",
                    'has_barang' => true,
                    'barang_count' => $kategori->barangs_count
                ], 400);
            }
            
            // Jika tidak memiliki barang, hapus kategori
            $kategori->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Kategori '{$kategori->name}' berhasil dihapus"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus kategori dengan force (pindahkan barang ke kategori lain dulu)
     */
    public function forceDeleteWithMove(Request $request, $id)
    {
        $request->validate([
            'new_kategori_id' => 'required|exists:kategori,id'
        ]);

        try {
            DB::beginTransaction();
            
            $kategori = Kategori::findOrFail($id);
            $newKategori = Kategori::findOrFail($request->new_kategori_id);
            
            // Pindahkan semua barang ke kategori baru
            Barang::where('kategori_id', $id)->update(['kategori_id' => $request->new_kategori_id]);
            
            // Hapus kategori lama
            $kategoriName = $kategori->name;
            $kategori->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Kategori '{$kategoriName}' berhasil dihapus dan semua barang telah dipindahkan ke kategori '{$newKategori->name}'"
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
     * Mengambil semua kategori untuk dropdown/pilihan
     */
    public function getOptions()
    {
        try {
            $kategoris = Kategori::select('id', 'name', 'code')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $kategoris
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil daftar kategori untuk dipilih saat force delete
     */
    public function getAvailableCategories($excludeId)
    {
        try {
            $kategoris = Kategori::where('id', '!=', $excludeId)
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $kategoris
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}