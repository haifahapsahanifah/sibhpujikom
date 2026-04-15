{{-- resources/views/exports/barang-keluar-print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Keluar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background: white;
        }
        
        .print-container {
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header h3 {
            font-size: 18px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .header .date-range {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        
        /* Info Summary */
        .info-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
            flex-wrap: wrap;
        }
        
        .info-item {
            text-align: center;
            flex: 1;
            min-width: 120px;
            padding: 5px;
        }
        
        .info-item .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        /* Tabel */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        td[style*="text-align: right"] {
            text-align: right !important;
        }
        
        /* Warna alternatif baris */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tfoot td {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        /* Button Container */
        .button-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        
        /* Button Print */
        .print-button {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .print-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        
        /* Button Back */
        .back-button {
            padding: 12px 24px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-button:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        
        /* Print styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .print-container {
                padding: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            th, td {
                page-break-inside: avoid;
            }
            
            .table-container {
                overflow: visible !important;
            }
        }
        
        /* Responsif untuk layar kecil */
        @media screen and (max-width: 768px) {
            .info-summary {
                flex-direction: column;
                gap: 10px;
            }
            
            .info-item {
                text-align: left;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .info-item .label {
                margin-bottom: 0;
            }
            
            table {
                font-size: 10px;
            }
            
            th, td {
                padding: 6px 4px;
            }
            
            .button-container {
                bottom: 10px;
                right: 10px;
            }
            
            .print-button, .back-button {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Button Container (hanya muncul di layar, tidak saat print) -->
        <div class="button-container no-print">
            <button class="print-button" onclick="window.print();">
                🖨️ Cetak / Print (Ctrl+P)
            </button>
            <button class="back-button" onclick="window.close();">
                ← Tutup Halaman
            </button>
        </div>
        
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN BARANG KELUAR</h1>
            <h3>Gudang {{ config('app.name', 'Aplikasi Gudang') }}</h3>
            <div class="date-range">
                @if(isset($tanggal_awal) && $tanggal_awal && isset($tanggal_akhir) && $tanggal_akhir)
                    Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                @elseif(isset($tanggal_awal) && $tanggal_awal)
                    Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} - Sekarang
                @else
                    Periode: Semua Data
                @endif
                
                @if(isset($divisi) && $divisi)
                    <br>Divisi: {{ $divisi }}
                @endif
            </div>
        </div>
        
        <!-- Info Summary -->
        <div class="info-summary">
            <div class="info-item">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ number_format($totalTransaksi ?? 0) }}</div>
            </div>
            <div class="info-item">
                <div class="label">Total Barang Keluar</div>
                <div class="value">{{ number_format($totalJumlah ?? 0) }}</div>
            </div>
            <div class="info-item">
                <div class="label">Total Divisi</div>
                <div class="value">{{ number_format($totalDivisi ?? 0) }}</div>
            </div>
            <div class="info-item">
                <div class="label">Tanggal Cetak</div>
                <div class="value">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="10%">Tanggal Keluar</th>
                        <th width="12%">Nomor Surat</th>
                        <th width="10%">Kode Barang</th>
                        <th width="15%">Nama Barang</th>
                        <th width="8%">Jumlah</th>
                        <th width="8%">Satuan</th>
                        <th width="12%">Penerima</th>
                        <th width="10%">Divisi</th>
                        <th width="10%">Keperluan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluaran ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d/m/Y') }}</td>
                        <td>{{ $item->nomor_surat ?? '-' }}</td>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td style="text-align: right;">{{ number_format($item->jumlah) }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>{{ $item->penerima ?? '-' }}</td>
                        <td>{{ $item->divisi ?? '-' }}</td>
                        <td>{{ Str::limit($item->keperluan, 30) ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center;">Tidak ada data barang keluar</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(isset($pengeluaran) && count($pengeluaran) > 0)
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalJumlah ?? 0) }}</strong></td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Dicetak oleh: {{ Auth::user()->name ?? 'System' }}</p>
            <p>Laporan ini dicetak secara otomatis dari sistem</p>
        </div>
    </div>
    
    <script>
        // Auto print jika parameter print=1
        if (window.location.search.includes('print=1')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
        
        // Shortcut keyboard Ctrl+P untuk print
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'p') {
                event.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>