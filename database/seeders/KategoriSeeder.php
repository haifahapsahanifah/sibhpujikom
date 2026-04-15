<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Kategori::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $kategoris = [
            [
                'name' => 'Alat Tulis Kantor',
                'code' => 'ATK',
                'description' => 'Perlengkapan alat tulis dan administrasi kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elektronik',
                'code' => 'ELEC',
                'description' => 'Peralatan elektronik dan komputer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Furniture',
                'code' => 'FURN',
                'description' => 'Perabotan kantor seperti meja, kursi, lemari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alat Kebersihan',
                'code' => 'CLEAN',
                'description' => 'Peralatan dan bahan kebersihan kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peralatan Teknik',
                'code' => 'TECH',
                'description' => 'Peralatan teknik dan perbengkelan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kendaraan',
                'code' => 'VEH',
                'description' => 'Kendaraan dinas dan operasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peralatan Medis',
                'code' => 'MED',
                'description' => 'Peralatan kesehatan dan medis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bahan Baku',
                'code' => 'RAW',
                'description' => 'Bahan baku untuk produksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peralatan Dapur',
                'code' => 'KITCH',
                'description' => 'Peralatan dan perlengkapan dapur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATK Cetak',
                'code' => 'PRINT',
                'description' => 'Kertas, tinta, dan perlengkapan cetak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data menggunakan insert untuk performa lebih baik
        Kategori::insert($kategoris);

        // Alternatif jika ingin menggunakan create (tapi lebih lambat untuk banyak data)
        // foreach ($kategoris as $kategori) {
        //     Kategori::create($kategori);
        // }

        $this->command->info('Seeder Kategori berhasil dijalankan!');
        $this->command->info('Total ' . count($kategoris) . ' kategori berhasil ditambahkan.');
    }
}