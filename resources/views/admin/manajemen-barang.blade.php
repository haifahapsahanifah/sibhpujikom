{{-- resources/views/admin/manajemen-barang.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Barang')
@section('page-title', 'Manajemen Barang')
@section('page-subtitle', 'Kelola stok dan informasi barang')

@section('content')
<div class="space-y-6">
    <!-- Statistik Barang -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBarang ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Stok Menipis</p>
                    <p class="text-2xl font-bold text-amber-600">{{ number_format($stokMenipis ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Stok Habis</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stokHabis ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Kategori</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalKategori ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fas fa-tags text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Barang dengan Manajemen -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Barang</h3>
                <div class="flex space-x-3 items-center">
                    <form action="{{ route('admin.manajemen-barang') }}" method="GET" class="flex items-center m-0">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700 transition">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.manajemen-barang') }}" class="ml-2 px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                    <a href="{{ route('admin.manajemen-barang.export') }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50 transition inline-block hidden md:block">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Min Stok</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Satuan</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    比
                </thead>
                <tbody>
                    @forelse($barangList as $barang)
                    @php
                        // Hitung stok dari barang masuk - barang keluar
                        $totalMasuk = App\Models\BarangMasuk::where('barang_id', $barang->id)->sum('jumlah');
                        $totalKeluar = App\Models\PengeluaranBarang::where('barang_id', $barang->id)->sum('jumlah');
                        $stok = $totalMasuk - $totalKeluar;
                        $stokMinimal = $barang->stok_minimal ?? 0;
                        
                        if ($stok <= 0) {
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'Habis';
                            $statusIcon = 'fa-times-circle';
                        } elseif ($stok <= $stokMinimal) {
                            $statusClass = 'bg-amber-100 text-amber-800';
                            $statusText = 'Menipis';
                            $statusIcon = 'fa-exclamation-triangle';
                        } else {
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Aman';
                            $statusIcon = 'fa-check-circle';
                        }
                        
                        $satuanName = $barang->satuan->name ?? 'pcs';
                        $kategoriName = $barang->kategori->name ?? '-';
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm font-medium">{{ $barang->kode_barang }}</td>
                        <td class="py-4 px-6 text-sm">{{ $barang->nama_barang }}</td>
                        <td class="py-4 px-6 text-sm">{{ $kategoriName }}</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="font-medium {{ $stok <= 0 ? 'text-red-600' : ($stok <= $stokMinimal ? 'text-amber-600' : 'text-gray-900') }}">
                                {{ number_format($stok, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm">{{ number_format($stokMinimal, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-sm">{{ $satuanName }}</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="px-3 py-1 {{ $statusClass }} text-xs rounded-full">
                                <i class="fas {{ $statusIcon }} mr-1"></i>{{ $statusText }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.barang.edit', $barang->id) }}" 
                                   class="p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.kartu.persediaan', ['barang_id' => $barang->id]) }}" 
                                   class="p-2 hover:bg-yellow-50 rounded-lg text-yellow-600 transition" 
                                   title="Kartu Persediaan">
                                    <i class="fas fa-history"></i>
                                </a>
                                <button onclick="deleteBarang({{ $barang->id }}, '{{ $barang->nama_barang }}')" 
                                        class="p-2 hover:bg-red-50 rounded-lg text-red-600 transition" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada data barang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($barangList, 'links'))
        <div class="p-6 border-t border-gray-100">
            {{ $barangList->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Hapus Barang -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Hapus Barang</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-center text-gray-700 mb-4">
                        Apakah Anda yakin ingin menghapus barang <strong id="barangName"></strong>?
                    </p>
                    <p class="text-center text-sm text-red-600">
                        Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
                
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentBarangId = null;

function deleteBarang(id, nama) {
    currentBarangId = id;
    document.getElementById('barangName').textContent = nama;
    document.getElementById('deleteForm').action = `/admin/barang/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentBarangId = null;
}

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection