<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Bukti Pengambilan - {{ $permintaan->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 10px;
            width: 80mm; /* Standard thermal printer width */
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }
        .m-0 { margin: 0; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .p-1 { padding: 5px; }
        .flex { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; vertical-align: top; }
        
        .no-print { display: none; }
        
        @media screen {
            body { 
                margin: 20px auto; 
                border: 1px solid #ccc;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .no-print { 
                display: block; 
                text-align: center; 
                margin-bottom: 20px;
                padding: 10px;
                background-color: #f8f9fa;
                border-bottom: 1px dashed #ccc;
            }
            .no-print button {
                padding: 5px 15px;
                background: #3b82f6;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .no-print a.btn-back {
                padding: 5px 15px;
                background: #6b7280;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
                margin-right: 10px;
                font-family: sans-serif;
                font-size: 14px;
            }
        }
        @media print {
            body { width: 100%; margin: 0; padding: 0; border: none; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        @if(isset($isAdminMode) && $isAdminMode)
            <a href="{{ route('admin.permintaan.index') }}" class="btn-back">Kembali</a>
            <button onclick="window.print()">🖨️ Cetak Struk</button>
        @else
            <a href="{{ route('user.permintaan.riwayat') }}" class="btn-back">Kembali ke Riwayat</a>
        @endif
    </div>
    
    <div class="text-center mb-2">
        <h2 class="m-0 bold">{{ \App\Models\Setting::get('app_name', 'SIBHP') }}</h2>
        <p class="m-0">{{ \App\Models\Setting::get('instansi_name', 'Nama Instansi') }}</p>
        <p class="m-0 text-center" style="font-size: 10px;">{{ \App\Models\Setting::get('instansi_address', 'Alamat Instansi') }}</p>
    </div>
    
    <div class="border-top border-bottom p-1 mb-2 mt-2">
        <p class="m-0 text-center bold">BUKTI PENGAMBILAN BARANG</p>
    </div>
    
    <div class="mb-2">
        <div class="flex"><span>No. Surat:</span> <span>{{ $permintaan->nomor_surat }}</span></div>
        <div class="flex"><span>Tanggal:</span> <span>{{ \Carbon\Carbon::parse($permintaan->diselesaikan_at ?? $permintaan->updated_at)->format('d/m/Y H:i') }}</span></div>
        <div class="flex"><span>Admin:</span> <span>{{ optional($permintaan->admin)->nama ?? '-' }}</span></div>
    </div>
    
    <div class="border-top mb-1 mt-1 pt-1 pb-1">
        <div class="flex"><span>Pemohon:</span> <span class="text-right">{{ optional($permintaan->user)->nama ?? '-' }}</span></div>
        <div class="flex"><span>Divisi:</span> <span class="text-right">{{ $permintaan->divisi }}</span></div>
    </div>
    
    <div class="border-top border-bottom pt-1 mb-2 pb-1">
        <table>
            <thead>
                <tr>
                    <th class="text-left border-bottom pb-1">Deskripsi Barang</th>
                    <th class="text-right border-bottom pb-1" style="width: 40px;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permintaan->details as $detail)
                @if($detail->disetujui_jumlah > 0)
                <tr>
                    <td class="pt-1">
                        {{ $detail->nama_barang }}
                        @if($detail->spesifikasi) <br><small>({{ $detail->spesifikasi }})</small> @endif
                    </td>
                    <td class="text-right pt-1">{{ $detail->disetujui_jumlah }} {{ $detail->satuan }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="text-center mt-2 mb-2">
        <p class="m-0 mb-1">Telah diserahkan dan</p>
        <p class="m-0">diterima dengan baik oleh,</p>
        <br><br><br>
        <p class="m-0 bold">({{ optional($permintaan->user)->nama ?? '.....................' }})</p>
    </div>
    
    <div class="text-center mt-2 border-top pt-2" style="font-size: 10px;">
        <p class="m-0">Harap simpan struk ini sebagai</p>
        <p class="m-0">bukti pengambilan yang sah.</p>
        <p class="m-0 mt-1">~ Dihasilkan oleh Sistem ~</p>
    </div>
    
    @if(isset($isAdminMode) && $isAdminMode)
    <script>
        // Otomatis cetak saat halaman pertama kali dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
    @endif
</body>
</html>
