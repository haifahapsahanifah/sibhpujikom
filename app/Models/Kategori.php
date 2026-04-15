<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori';
    
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi dengan tabel barang
     * Satu kategori memiliki banyak barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    /**
     * Scope untuk mencari kategori
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('code', 'LIKE', "%{$search}%");
    }

    /**
     * Cek apakah kategori memiliki barang
     */
    public function hasBarang()
    {
        return $this->barangs()->count() > 0;
    }

    /**
     * Mendapatkan jumlah barang dalam kategori ini
     */
    public function getBarangCountAttribute()
    {
        return $this->barangs()->count();
    }
}