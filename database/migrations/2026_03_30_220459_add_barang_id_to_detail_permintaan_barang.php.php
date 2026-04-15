<?php
// database/migrations/xxxx_xx_xx_add_barang_id_to_detail_permintaan_barang.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarangIdToDetailPermintaanBarang extends Migration
{
    public function up()
    {
        Schema::table('detail_permintaan_barang', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id')->nullable()->after('permintaan_barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('detail_permintaan_barang', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropColumn('barang_id');
        });
    }
}