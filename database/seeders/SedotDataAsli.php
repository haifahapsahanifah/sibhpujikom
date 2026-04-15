<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\BarangMasuk;

class SedotDataAsli extends Seeder
{
    public function run()
    {
        $data = [
            ['1.3.2.05.01.03.003', 'Lap Top', 'Lenovo IP 315IML05 Blue', 'Unit', 4, 7200000, 'Peralatan Komputer'],
            ['1.3.2.06.01.04.004', 'P.C Unit', 'LENOVO AIO V530 A - i7 10700T 8GB 1TB', 'Unit', 3, 14500000, 'Peralatan Komputer'],
            ['1.3.2.06.04.14.045', 'Jet Shower', 'JET SHOWER TOTO PUTIH', 'Unit', 2, 100000, 'Lain-lain Alat Peraga Kejuruan'],
            ['1.3.2.06.02.04.097', 'Karpet', 'Karpet Lantai Kamar Hotel Buah', 'Unit', 100, 198000, 'Alat Rumah Tangga'],
            ['1.3.2.05.01.02.001', 'Water Heater', 'Water Heater Gas Wasser WH-506', 'Unit', 2, 2560000, 'Alat Pemanas/Pendingin'],
            ['1.3.2.05.02.06.009', 'Tabung Gas', 'Tabung Gas Elpiji 12 Kg', 'Unit', 2, 220000, 'Alat Rumah Tangga'],
            ['1.3.2.05.05.04.006', 'Pompa Air', 'Pompa Air Shimizu', 'Unit', 1, 1200000, 'Alat Rumah Tangga'],
            ['1.3.2.05.02.01.001', 'Telepon Analog', 'PABX Panasonic KX - TES 824 8 Line', 'Unit', 1, 4460000, 'Alat Komunikasi'],
            ['1.3.2.05.02.06.024', 'Handy Talky (HT)', 'Handy Talky WLND C1', 'Unit', 4, 800000, 'Alat Komunikasi'],
            ['1.3.2.05.01.04.001', 'Audio Mixing Station', 'Mixer Audio Ashley Premium 4', 'Unit', 2, 1580000, 'Audio Visual'],
            ['1.3.2.05.02.04.001', 'Lemari Es', 'Kulkas 1 Pintu', 'Unit', 2, 2360000, 'Alat Rumah Tangga'],
            ['1.3.2.05.02.01.022', 'Hard Disk', 'Hardisk Eksternal 2TB', 'Unit', 2, 1560000, 'Peralatan Komputer'],
            ['1.3.2.05.02.01.093', 'LCD Projector', 'Projector Optoma', 'Unit', 2, 4650000, 'Audio Visual'],
            ['1.3.2.05.02.06.035', 'Microphone', 'Mic Wireless Ashley', 'Unit', 1, 750000, 'Audio Visual'],
            ['1.3.2.05.02.06.026', 'Megaphone', 'Toa Megaphone', 'Unit', 1, 600000, 'Audio Visual'],
            ['1.3.2.05.02.05.001', 'Vacuum Cleaner', 'Sharp EC-8305 Vacuum Cleaner', 'Unit', 5, 1250000, 'Alat Rumah Tangga'],
            ['1.3.2.05.02.06.085', 'Amplifier', 'Amplifier Power', 'Unit', 1, 1000000, 'Audio Visual'],
            ['1.3.2.05.01.05.342', 'Papan Tulis', 'Papan Tulis White Board 120x240', 'Buah', 15, 500000, 'Perabot Kantor'],
            ['1.3.2.05.01.05.343', 'Meja Kerja', 'Meja Kerja Kantor Modera', 'Unit', 3, 2200000, 'Mebel'],
            ['1.3.2.05.01.02.033', 'Kursi Lipat', 'Kursi Lipat Chitose', 'Unit', 70, 550000, 'Mebel'],
            ['1.3.2.05.01.04.098', 'Tool Cabinet Set', 'Tekiro Mechanical Tool Set', 'Set', 1, 10000000, 'Perkakas'],
            ['1.3.2.05.01.05.008', 'Tenda', 'Tenda Camping Dome', 'Buah', 20, 250000, 'Lain-lain'],
            ['1.3.2.05.02.01.006', 'Server', 'Server Dell PowerEdge R230', 'Unit', 1, 35000000, 'Peralatan Komputer'],
            ['1.3.2.06.01.04.102', 'Scanner', 'Scanner Brother ADS-2200', 'Unit', 1, 5500000, 'Peralatan Komputer'],
            ['1.3.2.06.08.01.026', 'Kamera Udara', 'Drone DJI', 'Unit', 1, 15000000, 'Peralatan Khusus'],
            ['1.3.2.06.02.04.024', 'Switch', 'Switch Hub Cisco', 'Unit', 1, 11500000, 'Peralatan Komputer'],
            ['1.3.2.06.04.01.001', 'Kamera Digital', 'Kamera Sony A7 Mark III', 'Unit', 1, 34500000, 'Audio Visual'],
            ['1.3.2.06.02.01.089', 'Lemari Penyimpanan', 'Lemari Besi 2 Pintu', 'Unit', 1, 7500000, 'Mebel'],
            ['1.3.2.06.02.04.032', 'Mesin Pemotong Rumput', 'Mesin Potong Rumput Firman', 'Unit', 1, 3200000, 'Peralatan Kebun'],
            ['1.3.2.06.03.01.033', 'LCD Monitor', 'Lenovo Think Vision', 'Unit', 1, 4200000, 'Peralatan Komputer'],
        ];

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                // $item mapping:
                // 0: Kode
                // 1: Nama Barang
                // 2: Spesifikasi
                // 3: Satuan
                // 4: Jumlah/Stok
                // 5: Harga
                // 6: Kategori
                
                // Cari atau buat kategori
                $kategori = Kategori::firstOrCreate(
                    ['name' => $item[6]],
                    [
                        'name' => $item[6],
                        'code' => strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $item[6]), 0, 4)) . rand(10,99),
                    ]
                );

                // Cari atau buat satuan
                $satuan = Satuan::firstOrCreate(
                    ['name' => $item[3]],
                    [
                        'name' => $item[3],
                        'code' => strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $item[3]), 0, 3)) . rand(10,99),
                    ]
                );

                // Entry Barang
                $barang = Barang::updateOrCreate(
                    ['kode_barang' => $item[0]],
                    [
                        'nama_barang' => $item[1],
                        'description' => $item[2],
                        'kategori_id' => $kategori->id,
                        'satuan_id' => $satuan->id,
                        'harga_satuan' => $item[5]
                    ]
                );

                // Set Stok Awal via Barang Masuk
                if ($barang->wasRecentlyCreated && $item[4] > 0) {
                    BarangMasuk::create([
                        'tanggal_masuk' => now()->format('Y-m-d'),
                        'nomor_dokumen' => 'STK-AWAL-' . rand(100, 999),
                        'nama_supplier' => 'Sistem Seeder',
                        'barang_id' => $barang->id,
                        'kode_barang' => $item[0],
                        'nama_barang' => $item[1],
                        'satuan_nama' => $item[3],
                        'nusp' => '-',
                        'spesifikasi_nama_barang' => $item[2],
                        'jumlah' => $item[4],
                        'harga_satuan' => $item[5],
                        'nilai_total' => $item[4] * $item[5],
                        'keterangan' => 'Stok awal dari database seeding',
                        'created_by' => 1 // ID User admin
                    ]);
                }
            }
            DB::commit();
            echo "Berhasil import " . count($data) . " barang dari data asli gambar.\n";
        } catch (\Exception $e) {
            DB::rollback();
            echo "Gagal: " . $e->getMessage() . "\n";
        }
    }
}
