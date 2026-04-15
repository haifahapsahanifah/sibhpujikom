<?php
// app/Models/DetailPermintaanBarang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanBarang extends Model
{
    use HasFactory;

    protected $table = 'detail_permintaan_barang';
    
    protected $fillable = [
        'permintaan_barang_id',
        'barang_id', // Tambahkan ini
        'kode_barang',
        'nama_barang',
        'spesifikasi',
        'pengajuan_jumlah',
        'satuan',
        'keperluan',
        'disetujui_jumlah',
        'disetujui_satuan',
        'status',
        'catatan_admin'
    ];

    protected $casts = [
        'pengajuan_jumlah' => 'integer',
        'disetujui_jumlah' => 'integer',
    ];

    // Accessor untuk mendapatkan nama satuan (tanpa JSON)
    public function getSatuanNameAttribute()
    {
        $satuan = $this->satuan;
        
        // Cek apakah satuan dalam format JSON
        if ($this->isJson($satuan)) {
            $data = json_decode($satuan, true);
            return $data['name'] ?? $data['satuan'] ?? 'pcs';
        }
        
        return $satuan;
    }

    // Helper untuk cek JSON
    private function isJson($string) {
        if (!is_string($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function permintaan()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id');
    }

    public function pengeluaran()
    {
        return $this->hasOne(PengeluaranBarang::class, 'detail_permintaan_id');
    }

     public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}