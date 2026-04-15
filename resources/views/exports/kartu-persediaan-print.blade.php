<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Persediaan</title>
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
        
        td.text-right, th.text-right {
            text-align: right !important;
        }
        
        td.text-center, th.text-center {
            text-align: center !important;
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
            <a href="{{ route('admin.kartu.persediaan') }}" class="back-button">
                ← Kembali ke Kartu Persediaan
            </a>
        </div>
        
        <!-- Header -->
        <div class="header">
            <h1>KARTU PERSEDIAAN BARANG</h1>
            <h3>Gudang {{ config('app.name', 'Aplikasi Gudang') }}</h3>
            <div class="date-range">
                Periode: {{ $tanggalAwal instanceof \Carbon\Carbon ? $tanggalAwal->format('d/m/Y') : date('d/m/Y', strtotime($tanggalAwal)) }} - {{ $tanggalAkhir instanceof \Carbon\Carbon ? $tanggalAkhir->format('d/m/Y') : date('d/m/Y', strtotime($tanggalAkhir)) }}
            </div>
        </div>

        @if(!$selectedBarang)
            <!-- Info Summary (Rekap Semua Barang) -->
            <div class="info-summary mb-4">
                <div class="info-item">
                    <div class="label">Total Barang</div>
                    <div class="value">{{ number_format(count($allBarangStats)) }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Total Nilai Persediaan</div>
                    <div class="value">Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Cetak</div>
                    <div class="value">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th class="text-right">Stok Awal</th>
                            <th class="text-right">Masuk</th>
                            <th class="text-right">Keluar</th>
                            <th class="text-right">Stok Akhir</th>
                            <th class="text-right">Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allBarangStats as $index => $stat)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $stat['kode_barang'] }}</td>
                            <td>{{ $stat['nama_barang'] }}</td>
                            <td>{{ $stat['kategori'] }}</td>
                            <td>{{ $stat['satuan'] }}</td>
                            <td class="text-right">{{ number_format($stat['stok_awal'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($stat['total_masuk'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($stat['total_keluar'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($stat['stok_akhir'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($stat['nilai_total'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">Tidak ada data barang</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($allBarangStats) > 0)
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">Total Keseluruhan</td>
                            <td class="text-right">{{ number_format($allBarangStats->sum('stok_awal'), 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($allBarangStats->sum('total_masuk'), 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($allBarangStats->sum('total_keluar'), 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($allBarangStats->sum('stok_akhir'), 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($allBarangStats->sum('nilai_total'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

        @else
            <!-- Tampilan Detail Satu Barang -->
            <div class="info-summary mb-4">
                <div class="info-item">
                    <div class="label">Kode Barang</div>
                    <div class="value">{{ $selectedBarang->kode_barang }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Nama Barang</div>
                    <div class="value">{{ $selectedBarang->nama_barang }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Kategori</div>
                    <div class="value">{{ $selectedBarang->kategori->name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Satuan</div>
                    <div class="value">{{ $selectedBarang->satuan->name ?? $selectedBarang->satuan_nama ?? 'pcs' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Posis Stok</div>
                    <div class="value">{{ number_format($stokAkhir, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Referensi</th>
                            <th>Keterangan</th>
                            <th class="text-right">Masuk</th>
                            <th class="text-right">Keluar</th>
                            <th class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>    
                            <td colspan="3" class="text-right font-bold">Saldo Awal</td>
                            <td></td>
                            <td></td>
                            <td class="text-right font-bold">{{ number_format($stokAwal, 0, ',', '.') }}</td>
                        </tr>
                        @forelse($mutasi as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->jenis == 'masuk')
                                    {{ $item->nomor_dokumen ?? '-' }}
                                @else
                                    {{ $item->nomor_surat ?? '-' }}
                                @endif
                            </td>
                            <td>
                                @if($item->jenis == 'masuk')
                                    {{ $item->keterangan ?? 'Pembelian Barang' }}
                                @else
                                    {{ $item->keperluan ?? 'Permintaan Barang' }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($item->jenis == 'masuk')
                                    {{ number_format($item->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">
                                @if($item->jenis == 'keluar')
                                    {{ number_format($item->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right font-bold">
                                {{ number_format($item->saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data mutasi untuk periode yang dipilih</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($mutasi) > 0)
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Total Mutasi</td>
                            <td class="text-right">{{ number_format($totalMasuk, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($totalKeluar, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($stokAkhir, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        @endif
        
        <!-- Footer Signature -->
        <div style="margin-top: 50px; display: flex; justify-content: space-between; text-align: center; font-size: 14px;">
            <div style="width: 30%;">
                <p>{{ \App\Models\Setting::get('ttd_pimpinan_jabatan', 'Pimpinan') }}</p>
                <div style="height: 80px;"></div>
                <p style="font-weight: bold; text-decoration: underline;">{{ \App\Models\Setting::get('ttd_pimpinan_nama', '....................') }}</p>
                <p>NIP. {{ \App\Models\Setting::get('ttd_pimpinan_nip', '....................') }}</p>
            </div>
            <div style="width: 30%;">
                <p>Mengetahui,</p>
                <p>{{ \App\Models\Setting::get('ttd_petugas_jabatan', 'Petugas Gudang') }}</p>
                <div style="height: 60px;"></div>
                <p style="font-weight: bold; text-decoration: underline;">{{ \App\Models\Setting::get('ttd_petugas_nama', '....................') }}</p>
                <p>NIP. {{ \App\Models\Setting::get('ttd_petugas_nip', '....................') }}</p>
            </div>
        </div>
        
        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <p>Mencetak menggunakan {{ \App\Models\Setting::get('instansi_nama', config('app.name', 'Aplikasi Gudang')) }}</p>
        </div>
    </div>
    
    <script>
        // Auto print
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
