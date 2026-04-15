{{-- resources/views/admin/barang/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Master Barang')
@section('page-title', 'Master Barang')
@section('page-subtitle', 'Kelola data barang inventaris')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <!-- Header dengan tombol tambah -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar Barang</h3>
                <p class="text-sm text-gray-500 mt-1">Total {{ number_format($totalBarang) }} barang terdaftar</p>
            </div>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Barang
            </button>
        </div>
    </div>
    
    <!-- Filter dan Search -->
    <div class="p-6 border-b border-gray-100">
        <form action="{{ route('admin.barang.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div class="flex gap-3">
                <select name="kategori_id" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                @if(request('search') || request('kategori_id'))
                    <a href="{{ route('admin.barang.index') }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Tabel Barang -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase rounded-l-lg">Kode Barang</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Satuan</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm font-medium text-gray-900">{{ $barang->kode_barang }}</td>
                        <td class="py-4 px-4 text-sm">{{ $barang->nama_barang }}</td>
                        <td class="py-4 px-4 text-sm">{{ $barang->kategori->name ?? '-' }}</td>
                        <td class="py-4 px-4 text-sm">{{ $barang->satuan->name ?? '-' }}</td>
                        <td class="py-4 px-4 text-sm">{{ $barang->harga_satuan ? 'Rp ' . number_format($barang->harga_satuan, 0, ',', '.') : 'Rp 0' }}</td>
                        <td class="py-4 px-4">
                            <div class="flex space-x-2">
                                <button onclick="editBarang({{ $barang->id }})" class="p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteBarang({{ $barang->id }}, '{{ $barang->nama_barang }}')" class="p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3"></i>
                            <p>Belum ada data barang</p>
                            <button onclick="openModal()" class="mt-2 text-blue-600 hover:text-blue-700">Tambah barang sekarang</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $barangs->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="barangModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Tambah Barang</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="barangForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="id" id="barangId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Barang *</label>
                    <input type="text" name="kode_barang" id="kode_barang" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500"
                           placeholder="Contoh: BRG001" required>
                    <p class="text-xs text-gray-500 mt-1">Kode barang harus unik</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang *</label>
                    <input type="text" name="nama_barang" id="nama_barang" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500"
                           placeholder="Nama barang" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                    <select name="kategori_id" id="kategori_id" 
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan *</label>
                    <select name="satuan_id" id="satuan_id" 
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" required>
                        <option value="">Pilih Satuan</option>
                        @foreach($satuans as $satuan)
                            <option value="{{ $satuan->id }}">{{ $satuan->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan *</label>
                    <input type="number" name="harga_satuan" id="harga_satuan" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500"
                           placeholder="0" min="0" step="1000" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500"
                              placeholder="Deskripsi barang (opsional)"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- resources/views/admin/barang/index.blade.php --}}
@push('scripts')
<script>
(function() {
    'use strict';
    
    // Cegah multiple initialization
    if (window._barangPageInitialized) {
        console.log('Barang page already initialized');
        return;
    }
    window._barangPageInitialized = true;
    
    console.log('Initializing Barang page');
    
    function init() {
        // Modal functions
        window.openModal = function() {
            const modal = document.getElementById('barangModal');
            if (modal) modal.classList.remove('hidden');
            const title = document.getElementById('modalTitle');
            if (title) title.innerText = 'Tambah Barang';
            const form = document.getElementById('barangForm');
            if (form) form.reset();
            const idInput = document.getElementById('barangId');
            if (idInput) idInput.value = '';
        };
        
        window.closeModal = function() {
            const modal = document.getElementById('barangModal');
            if (modal) modal.classList.add('hidden');
        };
        
        // Edit barang
        window.editBarang = function(id) {
            fetch(`/admin/barang/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const barang = data.data;
                    document.getElementById('barangId').value = barang.id;
                    document.getElementById('kode_barang').value = barang.kode_barang;
                    document.getElementById('nama_barang').value = barang.nama_barang;
                    document.getElementById('kategori_id').value = barang.kategori_id;
                    document.getElementById('satuan_id').value = barang.satuan_id;
                    document.getElementById('harga_satuan').value = barang.harga_satuan || 0;
                    document.getElementById('description').value = barang.description || '';
                    document.getElementById('modalTitle').innerText = 'Edit Barang';
                    window.openModal();
                } else {
                    if (window.showToast) {
                        window.showToast('error', 'Gagal memuat data barang');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.showToast) {
                    window.showToast('error', 'Terjadi kesalahan saat memuat data');
                }
            });
        };
        
        // Delete barang
        window.deleteBarang = function(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus barang "${name}"?`)) {
                fetch(`/admin/barang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (window.showToast) window.showToast('success', data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        if (window.showToast) window.showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (window.showToast) window.showToast('error', 'Terjadi kesalahan');
                });
            }
        };
        
        // Handle form submission
        const barangForm = document.getElementById('barangForm');
        if (barangForm) {
            const newForm = barangForm.cloneNode(true);
            barangForm.parentNode.replaceChild(newForm, barangForm);
            
            newForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = document.getElementById('barangId').value;
                const url = id ? `/admin/barang/${id}` : '/admin/barang';
                const method = id ? 'PUT' : 'POST';
                
                const formData = {
                    kode_barang: document.getElementById('kode_barang').value,
                    nama_barang: document.getElementById('nama_barang').value,
                    kategori_id: document.getElementById('kategori_id').value,
                    satuan_id: document.getElementById('satuan_id').value,
                    harga_satuan: document.getElementById('harga_satuan').value,
                    description: document.getElementById('description').value,
                    _token: document.querySelector('meta[name="csrf-token"]')?.content
                };
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (window.showToast) window.showToast('success', data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        if (data.errors) {
                            let errorMessages = '';
                            for (const key in data.errors) {
                                errorMessages += data.errors[key].join('\n') + '\n';
                            }
                            if (window.showToast) window.showToast('error', errorMessages);
                        } else {
                            if (window.showToast) window.showToast('error', data.message || 'Terjadi kesalahan');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (window.showToast) window.showToast('error', 'Terjadi kesalahan saat menyimpan data');
                });
            });
        }
    }
    
    // Jalankan init setelah DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
@endsection