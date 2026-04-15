{{-- resources/views/admin/laporan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Generate laporan inventaris')

@section('content')
<div class="space-y-6">
    <!-- Pilihan Laporan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card Laporan Stok -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all cursor-pointer">
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-4">
                <i class="fas fa-boxes text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900">Laporan Stok Barang</h3>
            <p class="text-sm text-gray-500 mt-2">Rekapitulasi stok barang per periode</p>
            <div class="mt-4">
                <span class="text-xs text-blue-600">Generate laporan →</span>
            </div>
        </div>

        <!-- Card Laporan Mutasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all cursor-pointer">
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-4">
                <i class="fas fa-exchange-alt text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900">Laporan Mutasi</h3>
            <p class="text-sm text-gray-500 mt-2">Riwayat barang masuk dan keluar</p>
            <div class="mt-4">
                <span class="text-xs text-green-600">Generate laporan →</span>
            </div>
        </div>

        <!-- Card Laporan Permintaan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all cursor-pointer">
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-4">
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900">Laporan Permintaan</h3>
            <p class="text-sm text-gray-500 mt-2">Rekapitulasi permintaan barang</p>
            <div class="mt-4">
                <span class="text-xs text-purple-600">Generate laporan →</span>
            </div>
        </div>
    </div>

    <!-- Form Generate Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Generate Laporan</h3>
        <form action="{{ route('admin.laporan') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                    <select name="jenis_laporan" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                        <option value="stok" {{ ($jenis_laporan ?? '') == 'stok' ? 'selected' : '' }}>Laporan Stok Barang</option>
                        <option value="permintaan" {{ ($jenis_laporan ?? '') == 'permintaan' ? 'selected' : '' }}>Laporan Permintaan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                    <select class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" disabled>
                        <option>Custom Tanggal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $start_date ?? date('Y-m-01') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $end_date ?? date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="submit" name="export" value="excel" class="px-6 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50">
                    <i class="fas fa-file-excel mr-2 text-green-600"></i>Unduh CSV/Excel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700">
                    <i class="fas fa-eye mr-2"></i>Preview
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Preview Laporan</h3>
        
        @if(isset($data) && count($data) > 0)
        <div class="border border-gray-200 rounded-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold uppercase">LAPORAN {{ str_replace('_', ' ', $jenis_laporan) }}</h2>
                <p class="text-sm text-gray-500">Periode: {{ date('d M Y', strtotime($start_date)) }} - {{ date('d M Y', strtotime($end_date)) }}</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        @if($jenis_laporan == 'stok')
                        <tr class="bg-gray-50">
                            <th class="text-left py-2 px-4 text-xs font-medium">Kode</th>
                            <th class="text-left py-2 px-4 text-xs font-medium">Nama Barang</th>
                            <th class="text-left py-2 px-4 text-xs font-medium">Kategori</th>
                            <th class="text-right py-2 px-4 text-xs font-medium">Stok Awal</th>
                            <th class="text-right py-2 px-4 text-xs font-medium">Masuk</th>
                            <th class="text-right py-2 px-4 text-xs font-medium">Keluar</th>
                            <th class="text-right py-2 px-4 text-xs font-medium">Stok Akhir</th>
                        </tr>
                        @elseif($jenis_laporan == 'permintaan')
                        <tr class="bg-gray-50">
                            <th class="text-left py-2 px-4 text-xs font-medium">Tanggal</th>
                            <th class="text-left py-2 px-4 text-xs font-medium">No. Surat</th>
                            <th class="text-left py-2 px-4 text-xs font-medium">Pemohon</th>
                            <th class="text-left py-2 px-4 text-xs font-medium">Barang & Qty</th>
                            <th class="text-left py-2 px-4 text-xs font-medium">Status</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if($jenis_laporan == 'stok')
                            @foreach($data as $row)
                            <tr class="border-b">
                                <td class="py-2 px-4 text-sm">{{ $row->kode }}</td>
                                <td class="py-2 px-4 text-sm">{{ $row->nama }}</td>
                                <td class="py-2 px-4 text-sm">{{ $row->kategori }}</td>
                                <td class="py-2 px-4 text-sm text-right">{{ $row->stok_awal }}</td>
                                <td class="py-2 px-4 text-sm text-right">{{ $row->masuk }}</td>
                                <td class="py-2 px-4 text-sm text-right">{{ $row->keluar }}</td>
                                <td class="py-2 px-4 text-sm text-right font-bold">{{ $row->stok_akhir }}</td>
                            </tr>
                            @endforeach
                        @elseif($jenis_laporan == 'permintaan')
                            @foreach($data as $row)
                            <tr class="border-b">
                                <td class="py-2 px-4 text-sm">{{ $row->created_at->format('Y-m-d') }}</td>
                                <td class="py-2 px-4 text-sm">{{ $row->nomor_surat }}</td>
                                <td class="py-2 px-4 text-sm">{{ $row->user->nama ?? '-' }}</td>
                                <td class="py-2 px-4 text-sm truncate max-w-xs">
                                    {{ $row->details->map(function($d) { return $d->nama_barang . ' ('.$d->disetujui_jumlah.')'; })->implode(', ') }}
                                </td>
                                <td class="py-2 px-4 text-sm">{{ ucfirst(str_replace('_', ' ', $row->status)) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
            <p>Pilih parameter dan klik Preview untuk menampilkan data laporan</p>
        </div>
        @endif
    </div>
</div>
@endsection