<?php
// app/Models/PengeluaranBarang.php (tambahkan relasi jika belum ada)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_barang';

    protected $fillable = [
        'permintaan_barang_id',
        'detail_permintaan_id',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'satuan',
        'penerima',
        'divisi',
        'tanggal_keluar',
        'keperluan',
        'nomor_surat',
        'created_by',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'jumlah' => 'integer',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id');
    }

    public function detail()
    {
        return $this->belongsTo(DetailPermintaanBarang::class, 'detail_permintaan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}