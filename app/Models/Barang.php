<?php
// app/Models/Barang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan_id',
        'harga_satuan',
        'description'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi ke Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    // Relasi ke BarangMasuk
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id');
    }

    // Relasi ke PengeluaranBarang
    public function pengeluaranBarang()
    {
        return $this->hasMany(PengeluaranBarang::class, 'barang_id');
    }

    // Accessor untuk mendapatkan nama satuan
    public function getSatuanNamaAttribute()
    {
        if ($this->satuan) {
            return $this->satuan->name;
        }
        return 'pcs';
    }

    // Accessor untuk mendapatkan nama kategori
    public function getKategoriNamaAttribute()
    {
        if ($this->kategori) {
            return $this->kategori->name;
        }
        return '-';
    }

    // Accessor untuk format harga
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Scope untuk mencari barang
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('kode_barang', 'LIKE', "%{$search}%")
                     ->orWhere('nama_barang', 'LIKE', "%{$search}%");
    }
}