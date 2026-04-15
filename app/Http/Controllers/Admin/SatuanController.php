<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    /**
     * Menampilkan daftar semua satuan
     */
    public function index()
    {
        $satuans = Satuan::orderBy('created_at', 'desc')->get();
        $totalSatuan = Satuan::count();
        
        return view('admin.satuan.index', compact('satuans', 'totalSatuan'));
    }

    /**
     * Menyimpan satuan baru ke database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:satuan,name',
            'code' => 'required|string|max:20|unique:satuan,code',
            'description' => 'nullable|string|max:255'
        ], [
            'name.required' => 'Nama satuan wajib diisi',
            'name.unique' => 'Nama satuan sudah digunakan',
            'code.required' => 'Kode satuan wajib diisi',
            'code.unique' => 'Kode satuan sudah digunakan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $satuan = Satuan::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil ditambahkan',
                'data' => $satuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail satu satuan
     */
    public function show($id)
    {
        try {
            $satuan = Satuan::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $satuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Satuan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Memperbarui data satuan
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:satuan,name,' . $id,
            'code' => 'required|string|max:20|unique:satuan,code,' . $id,
            'description' => 'nullable|string|max:255'
        ], [
            'name.required' => 'Nama satuan wajib diisi',
            'name.unique' => 'Nama satuan sudah digunakan',
            'code.required' => 'Kode satuan wajib diisi',
            'code.unique' => 'Kode satuan sudah digunakan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $satuan = Satuan::findOrFail($id);
            $satuan->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil diperbarui',
                'data' => $satuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus satuan dari database
     */
    public function destroy($id)
    {
        try {
            $satuan = Satuan::findOrFail($id);
            $satuan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil semua satuan untuk dropdown/pilihan
     */
    public function getOptions()
    {
        try {
            $satuans = Satuan::select('id', 'name', 'code')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $satuans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}