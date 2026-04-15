<?php
// app/Models/PermintaanBarang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBarang extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'permintaan_barang'; // <-- PERBAIKAN: nama tabel singular
    
    protected $fillable = [
        'nomor_surat',
        'user_id',
        'divisi',
        'tanggal_dibutuhkan',
        'prioritas',
        'status',
        'catatan',
        'lampiran',
        'disetujui_admin_at',
        'disetujui_admin_by',
        'disetujui_user_at',
        'ditolak_at',
        'alasan_ditolak'
    ];

    protected $casts = [
        'tanggal_dibutuhkan' => 'date',
        'disetujui_admin_at' => 'datetime',
        'disetujui_user_at' => 'datetime',
        'ditolak_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'disetujui_admin_by');
    }

    public function details()
    {
        return $this->hasMany(DetailPermintaanBarang::class, 'permintaan_barang_id');
    }

    public function pengeluaran()
    {
        return $this->hasMany(PengeluaranBarang::class, 'permintaan_barang_id');
    }

    public static function generateNomorSurat()
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)->count();
        $number = str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        return "SPB/{$number}/{$year}";
    }
}