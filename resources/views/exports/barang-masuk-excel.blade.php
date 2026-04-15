{{-- resources/views/exports/barang-masuk-excel.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Penerimaan Barang</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUKU PENERIMAAN BARANG</h1>
        @if($bulan)
            <h2>BULAN {{ strtoupper($bulan) }} {{ $tahun }}</h2>
        @endif
    </div>

    <div class="info">
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 200px;">Kuasa Pengguna Barang</td>
                <td style="border: none;">: {{ $kuasa_pengguna_barang ?? 'DINAS PENDIDIKAN PROVINSI JAWA BARAT' }}</td>
            </tr>
            <tr>
                <td style="border: none;">Pengguna Barang</td>
                <td style="border: none;">: {{ $pengguna_barang ?? 'SMAN 1 BATUJAJAR' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nomor Dokumen</th>
                <th>Nama Supplier</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>NUSP</th>
                <th>Spesifikasi</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Harga Satuan (Rp)</th>
                <th>Nilai Total (Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangMasuks as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</td>
                <td>{{ $item->nomor_dokumen ?? '-' }}</td>
                <td>{{ $item->nama_supplier ?? '-' }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->nusp ?? '-' }}</td>
                <td>{{ $item->spesifikasi_nama_barang ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->satuan_nama }}</td>
                <td class="text-right">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->nilai_total, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($barangMasuks) > 0)
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="11" class="text-right">Total Keseluruhan</td>
                <td class="text-right">Rp {{ number_format($barangMasuks->sum('nilai_total'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>