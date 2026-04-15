<?php
// database/migrations/2024_01_01_000001_create_barang_masuk_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangMasukTable extends Migration
{
    public function up()
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_masuk');
            $table->string('nomor_dokumen', 100)->nullable();
            $table->string('nama_supplier', 200)->nullable();
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->string('kode_barang', 50);
            $table->string('nama_barang', 200);
            $table->string('nusp', 100)->nullable();
            $table->string('spesifikasi_nama_barang', 100)->nullable();
            $table->integer('jumlah');
            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->string('satuan_nama', 50);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('nilai_total', 15, 2);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('set null');
            $table->foreign('satuan_id')->references('id')->on('satuan')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index('tanggal_masuk');
            $table->index('nomor_dokumen');
            $table->index('kode_barang');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('barang_masuk');
    }
}