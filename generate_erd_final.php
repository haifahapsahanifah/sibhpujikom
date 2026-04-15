<?php
$dir = 'c:/xampp/htdocs/sibhpujikom/dokumen_perancangan/';

function generateViaKroki($type, $code, $outfile) {
    global $dir;
    $ch = curl_init("https://kroki.io/$type/png");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $code);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/plain']);
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

// 2. Graphviz ERD Chen Final - IMPROVED AESTHETICS (Menggunakan layout dot)
$graphviz = 'graph ER {
    layout=dot;
    rankdir=LR;
    nodesep=0.7;
    ranksep=1.0;
    splines=ortho;
    
    node [fontname="Arial", style="filled", color="#2a3f54", fontcolor="white", penwidth=0];
    edge [color="#666666", penwidth=1.5];
    
    // Entities
    node [shape=rect, width=1.4, height=0.6, fontsize=12, style="filled,rounded"];
    USER [label="USERS"];
    PERMINTAAN [label="PERMINTAAN"];
    BARANG [label="BARANG"];
    KATEGORI [label="KATEGORI"];
    SATUAN [label="SATUAN"];
    
    // Relationships
    node [shape=diamond, width=1.1, height=1.1, margin=0, style="filled", shape=polygon, sides=4];
    MENGAJUKAN [label="Mengajukan"];
    DETAIL [label="Berisi Detail"];
    BERKATEGORI [label="Termasuk\nKategori"];
    BERSATUAN [label="Gunakan\nSatuan"];
    
    // Attributes
    node [shape=ellipse, width=1.0, height=0.4, fontsize=10, fillcolor="#34495e"];
    
    usr_id [label=<<u>id</u>>];
    usr_nama [label="nama"];
    usr_nip [label="nip"];
    
    req_id [label=<<u>id</u>>];
    req_no [label="no_surat"];
    req_status [label="status"];
    
    brg_id [label=<<u>id</u>>];
    brg_kode [label="kode"];
    brg_nama [label="nama_brg"];
    
    kat_id [label=<<u>id</u>>];
    kat_nama [label="nama"];
    
    sat_id [label=<<u>id</u>>];
    sat_nama [label="nama"];
    
    dtl_qty [label="jumlah"];

    // Connect Entities to attributes (invisible edges to guide rank if needed)
    USER -- usr_id; USER -- usr_nama; USER -- usr_nip;
    PERMINTAAN -- req_id; PERMINTAAN -- req_no; PERMINTAAN -- req_status;
    BARANG -- brg_id; BARANG -- brg_kode; BARANG -- brg_nama;
    KATEGORI -- kat_id; KATEGORI -- kat_nama;
    SATUAN -- sat_id; SATUAN -- sat_nama;
    
    // Detail attributes
    DETAIL -- dtl_qty [style=dashed];

    // Main Relationships (LR Flow)
    USER -- MENGAJUKAN [label="1", fontname="Arial", fontsize=10];
    MENGAJUKAN -- PERMINTAAN [label="N", fontname="Arial", fontsize=10];
    
    PERMINTAAN -- DETAIL [label="1", fontname="Arial", fontsize=10];
    DETAIL -- BARANG [label="M", fontname="Arial", fontsize=10];
    
    BARANG -- BERKATEGORI [label="N", fontname="Arial", fontsize=10];
    BERKATEGORI -- KATEGORI [label="1", fontname="Arial", fontsize=10];
    
    BARANG -- BERSATUAN [label="N", fontname="Arial", fontsize=10];
    BERSATUAN -- SATUAN [label="1", fontname="Arial", fontsize=10];
}';
generateViaKroki('graphviz', $graphviz, 'erd_model_chen_final.png');

echo "Update Chen Diagram Done!\n";
