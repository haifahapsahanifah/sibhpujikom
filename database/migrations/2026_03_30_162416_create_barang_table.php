<?php
// database/migrations/2024_01_01_000004_create_barang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique()->comment('Kode barang unik');
            $table->string('nama_barang', 200)->comment('Nama barang');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('restrict');
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('restrict');
            $table->decimal('harga_satuan', 15, 2)->default(0)->comment('Harga satuan barang');
            $table->text('description')->nullable()->comment('Deskripsi barang');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('kode_barang');
            $table->index('nama_barang');
            $table->index('kategori_id');
            $table->index('satuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};