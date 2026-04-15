@extends('layouts.admin')

@section('title', 'Kategori Barang')
@section('page-title', 'Kategori Barang')
@section('page-subtitle', 'Kelola kategori barang')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah/Edit Kategori -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6" id="form-title">Tambah Kategori</h3>
            <form id="kategori-form">
                @csrf
                <input type="hidden" id="kategori-id" name="kategori_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                               placeholder="Contoh: ATK, Elektronik, Furniture"
                               required>
                        <span class="text-sm text-red-600 error-message" id="name-error"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="code" name="code" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                               placeholder="Contoh: ATK, ELEC, FURN"
                               required>
                        <span class="text-sm text-red-600 error-message" id="code-error"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                  placeholder="Deskripsi kategori..."></textarea>
                        <span class="text-sm text-red-600 error-message" id="description-error"></span>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" id="submit-btn" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Kategori
                        </button>
                        <button type="button" id="cancel-btn" 
                                class="hidden flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Daftar Kategori -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kategori</h3>
                        <p class="text-sm text-gray-500 mt-1">Total <span id="total-kategori">{{ $totalKategori }}</span> kategori terdaftar</p>
                    </div>
                    <div class="relative">
                        <input type="text" id="search-kategori" 
                               class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm"
                               placeholder="Cari kategori...">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div id="kategori-list" class="grid grid-cols-1 gap-4">
                    @forelse($kategoris as $kategori)
                    <div class="kategori-card border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200" 
                         data-id="{{ $kategori->id }}"
                         data-name="{{ $kategori->name }}"
                         data-code="{{ $kategori->code }}"
                         data-description="{{ $kategori->description }}"
                         data-barang-count="{{ $kategori->barangs_count }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div>
                                    <h4 class="font-medium text-gray-900 text-lg">{{ $kategori->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $kategori->description ?: '-' }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full font-medium">
                                            <i class="fas fa-tag mr-1"></i> {{ $kategori->code }}
                                        </span>
                                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $kategori->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="inline-block px-2 py-1 {{ $kategori->barangs_count > 0 ? 'bg-orange-50 text-orange-700' : 'bg-green-50 text-green-700' }} text-xs rounded-full">
                                            <i class="fas fa-boxes mr-1"></i> {{ $kategori->barangs_count }} Barang
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button class="edit-btn p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition-colors" 
                                        data-id="{{ $kategori->id }}" 
                                        data-name="{{ $kategori->name }}"
                                        data-code="{{ $kategori->code }}"
                                        data-description="{{ $kategori->description }}"
                                        title="Edit Kategori">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-btn p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors" 
                                        data-id="{{ $kategori->id }}" 
                                        data-name="{{ $kategori->name }}"
                                        data-barang-count="{{ $kategori->barangs_count }}"
                                        title="Hapus Kategori">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 text-center py-8 text-gray-500" id="empty-state">
                        <i class="fas fa-folder-open text-5xl mb-3 opacity-50"></i>
                        <p class="text-lg font-medium">Belum ada data kategori</p>
                        <p class="text-sm mt-1">Silakan tambahkan kategori baru melalui form di samping</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk pindah barang sebelum hapus kategori -->
<div id="moveBarangModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 mx-4">
        <div class="mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Pindahkan Barang</h3>
            <p class="text-sm text-gray-600 mt-1">Kategori ini memiliki barang terkait. Silakan pilih kategori tujuan untuk memindahkan barang.</p>
        </div>
        
        <div class="mb-4 p-3 bg-yellow-50 rounded-lg">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Kategori <strong id="kategori-to-delete"></strong> memiliki <strong id="barang-count-display"></strong> barang.
            </p>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kategori Tujuan</label>
            <select id="new-kategori-id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                <option value="">-- Pilih Kategori --</option>
            </select>
        </div>
        
        <div class="flex gap-3">
            <button id="confirm-move-delete" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-medium transition">
                <i class="fas fa-trash-alt mr-2"></i> Pindahkan & Hapus
            </button>
            <button id="cancel-move" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-xl font-medium transition">
                <i class="fas fa-times mr-2"></i> Batal
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
.kategori-card {
    transition: all 0.3s ease;
}

.kategori-card:hover {
    transform: translateY(-2px);
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.9);
    }
}

.fade-out {
    animation: fadeOut 0.3s ease forwards;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Setup CSRF Token untuk AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    let kategoriToDelete = null;
    
    // Fungsi reset form
    function resetForm() {
        $('#kategori-form')[0].reset();
        $('#kategori-id').val('');
        $('#form-title').text('Tambah Kategori');
        $('#submit-btn').html('<i class="fas fa-save mr-2"></i> Simpan Kategori');
        $('#cancel-btn').addClass('hidden');
        $('.error-message').text('');
        $('input, textarea').removeClass('border-red-500');
    }
    
    // Fungsi update total count
    function updateTotalCount() {
        let total = $('.kategori-card').length;
        $('#total-kategori').text(total);
        if (total === 0 && $('#empty-state').length === 0) {
            $('#kategori-list').html(`
                <div class="col-span-1 text-center py-8 text-gray-500" id="empty-state">
                    <i class="fas fa-folder-open text-5xl mb-3 opacity-50"></i>
                    <p class="text-lg font-medium">Belum ada data kategori</p>
                    <p class="text-sm mt-1">Silakan tambahkan kategori baru melalui form di samping</p>
                </div>
            `);
        }
    }
    
    // Fungsi load kategori untuk modal
    function loadCategoriesForModal(excludeId) {
        $.ajax({
            url: '/admin/kategori/available/' + excludeId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">-- Pilih Kategori --</option>';
                    response.data.forEach(function(kategori) {
                        options += `<option value="${kategori.id}">${kategori.name} (${kategori.code})</option>`;
                    });
                    $('#new-kategori-id').html(options);
                }
            }
        });
    }
    
    // ========== FORM SUBMIT (TAMBAH/UPDATE) ==========
    $('#kategori-form').on('submit', function(e) {
        e.preventDefault();
        
        let id = $('#kategori-id').val();
        let url = id ? '/admin/kategori/' + id : '/admin/kategori';
        
        // Hapus pesan error sebelumnya
        $('.error-message').text('');
        $('input, textarea').removeClass('border-red-500');
        
        // Nonaktifkan tombol submit
        let submitBtn = $('#submit-btn');
        let originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
        
        // Prepare form data
        let formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('code', $('#code').val());
        formData.append('description', $('#description').val());
        
        if (id) {
            formData.append('_method', 'PUT');
        }
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`#${key}-error`).text(value[0]);
                        $(`#${key}`).addClass('border-red-500');
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Mohon periksa kembali input Anda',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // ========== EDIT KATEGORI ==========
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let code = $(this).data('code');
        let description = $(this).data('description');
        
        // Isi form dengan data kategori
        $('#kategori-id').val(id);
        $('#name').val(name);
        $('#code').val(code);
        $('#description').val(description || '');
        
        // Ubah tampilan form
        $('#form-title').text('Edit Kategori');
        $('#submit-btn').html('<i class="fas fa-save mr-2"></i> Update Kategori');
        $('#cancel-btn').removeClass('hidden');
        
        // Hapus class error jika ada
        $('input, textarea').removeClass('border-red-500');
        $('.error-message').text('');
        
        // Scroll ke form
        $('html, body').animate({
            scrollTop: $('.lg\\:col-span-1').offset().top - 20
        }, 500);
        
        // Tampilkan notifikasi
        Swal.fire({
            icon: 'info',
            title: 'Mode Edit',
            text: `Sedang mengedit kategori: ${name}`,
            timer: 2000,
            showConfirmButton: false
        });
    });
    
    // ========== HAPUS KATEGORI (Dengan Pengecekan) ==========
    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        let kategoriName = $(this).data('name');
        let barangCount = $(this).data('barang-count');
        let card = $(this).closest('.kategori-card');
        
        if (barangCount > 0) {
            // Kategori memiliki barang, tampilkan opsi pindah
            Swal.fire({
                title: 'Kategori Memiliki Barang!',
                html: `
                    <div class="text-left">
                        <p class="mb-2">Kategori <strong class="text-red-600">"${kategoriName}"</strong> memiliki <strong>${barangCount} barang</strong> yang terkait.</p>
                        <p class="text-sm text-gray-600">Kategori tidak dapat dihapus sebelum semua barang dipindahkan atau dihapus.</p>
                        <hr class="my-3">
                        <p class="text-sm font-medium">Pilih tindakan:</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-exchange-alt mr-2"></i> Pindahkan Barang & Hapus',
                cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan modal untuk pindah barang
                    kategoriToDelete = {
                        id: id,
                        name: kategoriName,
                        barangCount: barangCount
                    };
                    $('#kategori-to-delete').text(kategoriName);
                    $('#barang-count-display').text(barangCount);
                    loadCategoriesForModal(id);
                    $('#moveBarangModal').removeClass('hidden').addClass('flex');
                }
            });
        } else {
            // Kategori tidak memiliki barang, hapus langsung
            Swal.fire({
                title: 'Hapus Kategori',
                html: `
                    <div class="text-left">
                        <p>Apakah Anda yakin ingin menghapus kategori:</p>
                        <p class="font-bold text-red-600 my-2">"${kategoriName}"</p>
                        <p class="text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan!</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performDelete(id, card, kategoriName);
                }
            });
        }
    });
    
    // Fungsi untuk melakukan hapus
    function performDelete(id, card, kategoriName) {
        Swal.fire({
            title: 'Menghapus...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '/admin/kategori/' + id,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // Animasi hapus card
                    card.fadeOut(300, function() {
                        $(this).remove();
                        updateTotalCount();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghapus!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }
    
    // ========== KONFIRMASI PINDAH & HAPUS ==========
    $('#confirm-move-delete').on('click', function() {
        let newKategoriId = $('#new-kategori-id').val();
        
        if (!newKategoriId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Kategori Tujuan',
                text: 'Silakan pilih kategori tujuan untuk memindahkan barang',
                confirmButtonColor: '#3085d6'
            });
            return;
        }
        
        Swal.fire({
            title: 'Pindahkan & Hapus?',
            html: `
                <div class="text-left">
                    <p>Semua barang dari kategori <strong>"${kategoriToDelete.name}"</strong> akan dipindahkan ke kategori terpilih.</p>
                    <p class="text-sm text-red-600 mt-2">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-check mr-2"></i> Ya, Lanjutkan',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#moveBarangModal').addClass('hidden').removeClass('flex');
                
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Memindahkan barang dan menghapus kategori',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '/admin/kategori/' + kategoriToDelete.id + '/force-delete',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        new_kategori_id: newKategoriId
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    });
    
    // ========== BATAL MODAL ==========
    $('#cancel-move').on('click', function() {
        $('#moveBarangModal').addClass('hidden').removeClass('flex');
        kategoriToDelete = null;
    });
    
    // ========== BATAL EDIT ==========
    $('#cancel-btn').on('click', function() {
        Swal.fire({
            title: 'Batal Edit?',
            text: 'Perubahan yang belum disimpan akan hilang',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batal',
            cancelButtonText: 'Lanjut Edit'
        }).then((result) => {
            if (result.isConfirmed) {
                resetForm();
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Edit kategori dibatalkan',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });
    
    // ========== SEARCH KATEGORI ==========
    $('#search-kategori').on('keyup', function() {
        let searchTerm = $(this).val().toLowerCase();
        
        $('.kategori-card').each(function() {
            let name = $(this).data('name').toLowerCase();
            let code = $(this).data('code').toLowerCase();
            
            if (name.includes(searchTerm) || code.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Tampilkan pesan jika tidak ada hasil
        let visibleCards = $('.kategori-card:visible').length;
        if (visibleCards === 0 && $('.kategori-card').length > 0) {
            if ($('#no-search-result').length === 0) {
                $('#kategori-list').append(`
                    <div id="no-search-result" class="col-span-1 text-center py-8 text-gray-500">
                        <i class="fas fa-search text-4xl mb-3 opacity-50"></i>
                        <p>Tidak ada kategori yang ditemukan</p>
                        <p class="text-sm">Coba dengan kata kunci lain</p>
                    </div>
                `);
            }
        } else {
            $('#no-search-result').remove();
        }
    });
    
    // ========== AUTO UPPERCASE UNTUK KODE ==========
    $('#code').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
    
    // ========== HAPUS CLASS ERROR SAAT MULAI MENGETIK ==========
    $('input, textarea').on('focus', function() {
        $(this).removeClass('border-red-500');
        $(this).siblings('.error-message').text('');
    });
    
    console.log('Kategori page initialized');
});
</script>
@endpush

@endsection