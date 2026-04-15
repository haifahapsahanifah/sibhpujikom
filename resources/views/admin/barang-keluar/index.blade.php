{{-- resources/views/admin/barang-keluar/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Barang Keluar')
@section('page-title', 'Riwayat Barang Keluar')
@section('page-subtitle', 'Daftar barang keluar yang telah disetujui')

@section('content')
<div class="space-y-6">
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Bulan Ini</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['bulan_ini'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Barang Keluar</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['total_jumlah'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.barang-keluar.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-full md:col-span-4 mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" placeholder="Cari No. Surat, Kode/Nama Barang, Penerima...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" 
                       class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                       class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                <select name="divisi" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                    <option value="">Semua Divisi</option>
                    <option value="HRD" {{ request('divisi') == 'HRD' ? 'selected' : '' }}>HRD</option>
                    <option value="Keuangan" {{ request('divisi') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
                    <option value="IT" {{ request('divisi') == 'IT' ? 'selected' : '' }}>IT</option>
                    <option value="Marketing" {{ request('divisi') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="Operasional" {{ request('divisi') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                    <option value="Umum" {{ request('divisi') == 'Umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.barang-keluar.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

        <!-- Print Button -->
    <div class="flex justify-end">
        <a href="{{ route('admin.barang-keluar.print', request()->all()) }}" 
           target="_blank"
           class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center">
            <i class="fas fa-print mr-2"></i>Cetak Laporan
        </a>
    </div>
    
    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No. SPB</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Penerima</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Divisi</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Keperluan</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluaran as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d/m/Y') }}</td>
                        <td class="py-4 px-6 text-sm font-medium">{{ $item->nomor_surat }}</td>
                        <td class="py-4 px-6 text-sm">{{ $item->nama_barang }}</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="font-semibold text-amber-600">{{ $item->jumlah }} {{ $item->satuan }}</span>
                        </td>
                        <td class="py-4 px-6 text-sm">{{ $item->penerima }}</td>
                        <td class="py-4 px-6 text-sm">{{ $item->divisi }}</td>
                        <td class="py-4 px-6 text-sm">{{ Str::limit($item->keperluan, 30) }}</td>
                        <td class="py-4 px-6">
                            <button onclick="showDetail({{ $item->id }})" 
                                    class="text-blue-600 hover:text-blue-800 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-5xl text-gray-300 mb-3"></i>
                            <p>Belum ada data barang keluar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($pengeluaran) && method_exists($pengeluaran, 'links'))
        <div class="p-6 border-t border-gray-100">
            {{ $pengeluaran->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Detail Barang Keluar</h3>
                <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4" id="detailContent">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="mt-2 text-gray-500">Memuat data...</p>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex justify-end">
                <button onclick="closeDetail()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(id) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    if (!modal || !content) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-500">Memuat data...</p></div>';
    
    fetch(`/admin/barang-keluar/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success && data.data) {
            renderDetail(data.data);
        } else {
            content.innerHTML = `<div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                <p>Gagal memuat data: ${data.message || 'Data tidak valid'}</p>
                <button onclick="closeDetail()" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg">Tutup</button>
            </div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.innerHTML = `<div class="text-center py-8 text-red-500">
            <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
            <p>Gagal memuat data: ${error.message}</p>
            <button onclick="closeDetail()" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg">Tutup</button>
        </div>`;
    });
}

function renderDetail(pengeluaran) {
    const content = document.getElementById('detailContent');
    
    if (!content) return;
    
    let tanggalKeluar = '';
    let tanggalDibuat = '';
    
    try {
        if (pengeluaran.tanggal_keluar) {
            tanggalKeluar = new Date(pengeluaran.tanggal_keluar).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        if (pengeluaran.created_at) {
            tanggalDibuat = new Date(pengeluaran.created_at).toLocaleString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    } catch(e) {
        tanggalKeluar = pengeluaran.tanggal_keluar || '-';
        tanggalDibuat = pengeluaran.created_at || '-';
    }
    
    const html = `
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">No. Surat</p>
                <p class="font-medium">${escapeHtml(pengeluaran.nomor_surat)}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Tanggal Keluar</p>
                <p class="font-medium">${tanggalKeluar}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Kode Barang</p>
                <p class="font-medium">${escapeHtml(pengeluaran.kode_barang)}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Nama Barang</p>
                <p class="font-medium">${escapeHtml(pengeluaran.nama_barang)}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Jumlah</p>
                <p class="font-medium text-amber-600 text-lg">${pengeluaran.jumlah} ${escapeHtml(pengeluaran.satuan)}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Penerima</p>
                <p class="font-medium">${escapeHtml(pengeluaran.penerima)}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Divisi</p>
                <p class="font-medium">${escapeHtml(pengeluaran.divisi)}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Dibuat Oleh</p>
                <p class="font-medium">${pengeluaran.created_by ? escapeHtml(pengeluaran.created_by.nama) : '-'}</p>
                <p class="text-xs text-gray-500">${tanggalDibuat}</p>
            </div>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Keperluan</p>
            <p class="text-sm mt-1">${escapeHtml(pengeluaran.keperluan)}</p>
        </div>
        ${pengeluaran.keterangan ? `
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Keterangan</p>
            <p class="text-sm mt-1">${escapeHtml(pengeluaran.keterangan)}</p>
        </div>
        ` : ''}
    `;
    
    content.innerHTML = html;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function closeDetail() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetail();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetail();
    }
});
</script>
@endsection