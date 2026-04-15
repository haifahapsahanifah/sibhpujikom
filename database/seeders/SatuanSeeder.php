<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satuan;
use Illuminate\Support\Facades\Schema;

class SatuanSeeder extends Seeder
{
    /**
     * Menjalankan seeder
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();
        Satuan::truncate();
        Schema::enableForeignKeyConstraints();

        $satuans = [
            // Satuan Umum
            [
                'name' => 'Pieces',
                'code' => 'PCS',
                'description' => 'Pieces atau buah, digunakan untuk barang yang dihitung per unit'
            ],
            [
                'name' => 'Lusin',
                'code' => 'LSN',
                'description' => '1 lusin = 12 buah, biasanya untuk pakaian, aksesoris'
            ],
            [
                'name' => 'Kodi',
                'code' => 'KDI',
                'description' => '1 kodi = 20 buah, biasanya untuk kain, pakaian'
            ],
            [
                'name' => 'Gross',
                'code' => 'GRS',
                'description' => '1 gross = 144 buah = 12 lusin'
            ],
            
            // Satuan Berat
            [
                'name' => 'Kilogram',
                'code' => 'KG',
                'description' => 'Kilogram, satuan berat untuk barang-barang seperti beras, gula, tepung'
            ],
            [
                'name' => 'Gram',
                'code' => 'GR',
                'description' => 'Gram, satuan berat untuk barang-barang ringan seperti emas, obat-obatan'
            ],
            [
                'name' => 'Ons',
                'code' => 'ONS',
                'description' => 'Ons, 1 ons = 100 gram'
            ],
            [
                'name' => 'Kwintal',
                'code' => 'KWL',
                'description' => 'Kwintal, 1 kwintal = 100 kg, biasanya untuk hasil pertanian'
            ],
            [
                'name' => 'Ton',
                'code' => 'TON',
                'description' => 'Ton, 1 ton = 1000 kg, untuk barang-barang berat'
            ],
            
            // Satuan Volume/Cairan
            [
                'name' => 'Liter',
                'code' => 'LTR',
                'description' => 'Liter, satuan volume untuk cairan seperti air, minyak, bahan bakar'
            ],
            [
                'name' => 'Mililiter',
                'code' => 'ML',
                'description' => 'Mililiter, 1 liter = 1000 ml, untuk cairan dalam jumlah kecil'
            ],
            [
                'name' => 'Gallon',
                'code' => 'GLN',
                'description' => 'Gallon, 1 gallon = 3.785 liter (US) atau 4.546 liter (UK)'
            ],
            
            // Satuan Panjang
            [
                'name' => 'Meter',
                'code' => 'M',
                'description' => 'Meter, satuan panjang untuk kain, kabel, pipa'
            ],
            [
                'name' => 'Centimeter',
                'code' => 'CM',
                'description' => 'Centimeter, 1 meter = 100 cm'
            ],
            [
                'name' => 'Inch',
                'code' => 'IN',
                'description' => 'Inch, 1 inch = 2.54 cm, untuk ukuran layar, pipa'
            ],
            [
                'name' => 'Roll',
                'code' => 'RL',
                'description' => 'Roll, untuk barang yang digulung seperti kabel, kertas, plastik'
            ],
            
            // Satuan Kemasan
            [
                'name' => 'Box',
                'code' => 'BOX',
                'description' => 'Box atau kardus, kemasan berbentuk kotak'
            ],
            [
                'name' => 'Pack',
                'code' => 'PCK',
                'description' => 'Pack atau paket, kemasan berisi beberapa item'
            ],
            [
                'name' => 'Set',
                'code' => 'SET',
                'description' => 'Set, kumpulan beberapa item yang menjadi satu kesatuan'
            ],
            [
                'name' => 'Dus',
                'code' => 'DUS',
                'description' => 'Dus, kemasan kardus yang lebih besar'
            ],
            [
                'name' => 'Karung',
                'code' => 'KRG',
                'description' => 'Karung, kemasan dari bahan goni atau plastik untuk beras, pupuk'
            ],
            [
                'name' => 'Sak',
                'code' => 'SAK',
                'description' => 'Sak, kemasan dari bahan plastik tebal untuk semen, pupuk'
            ],
            
            // Satuan Kertas & Percetakan
            [
                'name' => 'Rim',
                'code' => 'RM',
                'description' => 'Rim, 1 rim = 500 lembar, untuk kertas'
            ],
            [
                'name' => 'Lembar',
                'code' => 'LBR',
                'description' => 'Lembar, untuk kertas atau dokumen'
            ],
            [
                'name' => 'Bendel',
                'code' => 'BDL',
                'description' => 'Bendel, ikatan kertas dalam jumlah tertentu'
            ],
            
            // Satuan Khusus
            [
                'name' => 'Unit',
                'code' => 'UNT',
                'description' => 'Unit, untuk perangkat elektronik, mesin, kendaraan'
            ],
            [
                'name' => 'Pasang',
                'code' => 'PSG',
                'description' => 'Pasang, untuk barang yang berpasangan seperti sepatu, sandal'
            ],
            [
                'name' => 'Batang',
                'code' => 'BTG',
                'description' => 'Batang, untuk barang berbentuk batang seperti besi, kayu, pipa'
            ],
            [
                'name' => 'Butir',
                'code' => 'BTR',
                'description' => 'Butir, untuk barang kecil seperti telur, obat-obatan'
            ],
            [
                'name' => 'Helai',
                'code' => 'HLI',
                'description' => 'Helai, untuk barang tipis seperti kain, kertas, daun'
            ],
            [
                'name' => 'Buah',
                'code' => 'BUA',
                'description' => 'Buah, untuk barang yang dihitung per unit'
            ],
            [
                'name' => 'Biji',
                'code' => 'BJ',
                'description' => 'Biji, untuk barang yang berbentuk bulat kecil'
            ],
            [
                'name' => 'Ekor',
                'code' => 'EKR',
                'description' => 'Ekor, untuk hewan ternak atau ikan'
            ],
            
            // Satuan Waktu (untuk jasa/sewa)
            [
                'name' => 'Hari',
                'code' => 'HR',
                'description' => 'Hari, untuk jasa sewa harian'
            ],
            [
                'name' => 'Bulan',
                'code' => 'BLN',
                'description' => 'Bulan, untuk jasa sewa bulanan'
            ],
            [
                'name' => 'Tahun',
                'code' => 'THN',
                'description' => 'Tahun, untuk jasa sewa tahunan'
            ],
            [
                'name' => 'Jam',
                'code' => 'JAM',
                'description' => 'Jam, untuk jasa per jam'
            ],
            
            // Satuan Lainnya
            [
                'name' => 'Item',
                'code' => 'ITM',
                'description' => 'Item, untuk barang dalam sistem inventory'
            ],
            [
                'name' => 'Paket',
                'code' => 'PKT',
                'description' => 'Paket, bundling beberapa item dalam satu kemasan'
            ],
            [
                'name' => 'Slop',
                'code' => 'SLP',
                'description' => 'Slop, untuk produk yang dibungkus plastik'
            ],
            [
                'name' => 'Tube',
                'code' => 'TUB',
                'description' => 'Tube, untuk produk dalam kemasan tube seperti pasta, krim'
            ],
            [
                'name' => 'Botol',
                'code' => 'BTL',
                'description' => 'Botol, untuk produk dalam kemasan botol'
            ],
            [
                'name' => 'Kaleng',
                'code' => 'KLG',
                'description' => 'Kaleng, untuk produk dalam kemasan kaleng'
            ],
        ];

        foreach ($satuans as $satuan) {
            Satuan::create($satuan);
        }
        
        $this->command->info('Seeder satuan berhasil dijalankan!');
        $this->command->info('Total ' . count($satuans) . ' satuan telah ditambahkan.');
    }
}