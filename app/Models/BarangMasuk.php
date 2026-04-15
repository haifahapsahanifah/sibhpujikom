<?php
// app/Models/BarangMasuk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'tanggal_masuk',
        'nomor_dokumen',
        'nama_supplier',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'nusp',
        'spesifikasi_nama_barang',
        'jumlah',
        'satuan_id',
        'satuan_nama',
        'harga_satuan',
        'nilai_total',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'nilai_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->jumlah && $model->harga_satuan) {
                $model->nilai_total = $model->jumlah * $model->harga_satuan;
            }
            
        });

        static::updating(function ($model) {
            if ($model->jumlah && $model->harga_satuan) {
                $model->nilai_total = $model->jumlah * $model->harga_satuan;
            }
        });

    }

    // app/Http/Controllers/Admin/BarangMasukController.php

public function export(Request $request)
{
    try {
        $query = BarangMasuk::with(['barang', 'satuan', 'createdBy'])
            ->orderBy('tanggal_masuk', 'asc');
        
        // Filter berdasarkan bulan dan tahun jika ada
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }
        
        // Atau filter berdasarkan rentang tanggal
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
        }
        
        $barangMasuks = $query->get();
        
        // Data untuk kop surat/kepala laporan
        $data = [
            'barangMasuks' => $barangMasuks,
            'bulan' => $request->bulan ? $this->getBulanName($request->bulan) : null,
            'tahun' => $request->tahun ?: date('Y'),
            'tanggal_cetak' => now(),
            'kuasa_pengguna_barang' => 'DINAS PENDIDIKAN PROVINSI JAWA BARAT',
            'pengguna_barang' => 'SMAN 1 BATUJAJAR',
            'kepala_sekolah' => auth()->user()->name ?? 'Admin',
            'pengurus_barang' => auth()->user()->name ?? 'Staff'
        ];
        
        // Generate PDF atau Excel
        if ($request->has('format') && $request->format === 'excel') {
            return $this->exportToExcel($data);
        }
        
        return $this->exportToPdf($data);
        
    } catch (\Exception $e) {
        \Log::error('Export error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengexport data: ' . $e->getMessage()
        ], 500);
    }
}

private function getBulanName($bulan)
{
    $bulanNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    return $bulanNames[$bulan] ?? '';
}

private function exportToPdf($data)
{
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.barang-masuk-pdf', $data);
    $pdf->setPaper('A4', 'landscape');
    
    $filename = 'buku_penerimaan_barang_' . date('Ymd_His') . '.pdf';
    return $pdf->download($filename);
}

private function exportToExcel($data)
{
    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\BarangMasukExport($data),
        'buku_penerimaan_barang_' . date('Ymd_His') . '.xlsx'
    );
}
}