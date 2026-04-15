<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'satuan';
    
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
     * Relasi dengan model Barang - Sementara dikomentari
     */
    // public function barangs()
    // {
    //     return $this->hasMany(Barang::class, 'satuan_id');
    // }

    /**
     * Scope untuk mencari satuan
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('code', 'LIKE', "%{$search}%");
    }
}