<?php
$dir = 'c:/xampp/htdocs/sibhpujikom/dokumen_perancangan/';

function generateViaKroki($type, $code, $outfile) {
    global $dir;
    $ch = curl_init("https://kroki.io/$type/png");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $code);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/plain']);
    
    // Specifically ignore SSL verify if Windows issues
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpcode == 200) {
        file_put_contents($dir . $outfile, $result);
        echo "Tersimpan: $outfile\n";
    } else {
        echo "Gagal ($httpcode): $outfile\n";
    }
}

// 1. Mermaid ERD
$mermaid = "erDiagram
    PENGGUNA ||--o{ PERMINTAAN : membuat
    PENGGUNA {
        int id PK
        string nama
        string email
        string peran
    }
    BARANG ||--o{ PERMINTAAN_DETAIL : memiliki
    BARANG {
        string kode_barang PK
        string nama_barang
        int stok_tersedia
    }
    PERMINTAAN ||--|{ PERMINTAAN_DETAIL : memiliki
    PERMINTAAN {
        int id_permintaan PK
        date tanggal
        string status
    }
    PERMINTAAN_DETAIL {
        int id_detail PK
        int jumlah
    }
";
generateViaKroki('mermaid', $mermaid, 'erd_model_tabel_awal.png');

// 2. PlantUML Usecase
$plantuml = "@startuml
left to right direction
skinparam packageStyle rectangle
actor Admin
actor Pegawai
rectangle \"Sistem SIBHP (Perancangan Awal)\" {
  Pegawai -- (Login)
  Admin -- (Login)
  Pegawai -- (Lihat Daftar Barang)
  Pegawai -- (Minta Barang)
  (Minta Barang) .> (Lihat Daftar Barang) : include
  Admin -- (Kelola Master Data)
  Admin -- (Setujui Permintaan)
}
@enduml";
generateViaKroki('plantuml', $plantuml, 'usecase_diagram_awal.png');

echo "Selesai.\n";

