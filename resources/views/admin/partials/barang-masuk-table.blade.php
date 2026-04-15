@forelse($barangMasuks as $index => $item)
<tr class="hover:bg-gray-50">
    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + $barangMasuks->firstItem() }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nomor_dokumen ?? '-' }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama_supplier ?? '-' }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->kode_barang }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama_barang }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nusp ?? '-' }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->spesifikasi_nama_barang ?? '-' }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($item->jumlah) }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->satuan_nama }}</td>
    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-sm font-medium text-green-600">Rp {{ number_format($item->nilai_total, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-sm">
        <button onclick="deleteBarangMasuk({{ $item->id }})" class="text-red-600 hover:text-red-800 transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="13" class="px-4 py-8 text-center text-gray-500">
        <i class="fas fa-box-open text-4xl mb-2 block"></i>
        Belum ada data barang masuk
    </td>
</tr>
@endforelse