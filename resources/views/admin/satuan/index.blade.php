{{-- resources/views/admin/satuan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Satuan Barang')
@section('page-title', 'Satuan Barang')
@section('page-subtitle', 'Kelola satuan barang')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah/Edit Satuan -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6" id="form-title">Tambah Satuan</h3>
            <form id="satuan-form">
                @csrf
                <input type="hidden" id="satuan-id" name="satuan_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                               placeholder="Contoh: Pcs, Rim, Box"
                               required>
                        <span class="text-sm text-red-600 error-message" id="name-error"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="code" name="code" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                               placeholder="Contoh: PCS, RM, BOX"
                               required>
                        <span class="text-sm text-red-600 error-message" id="code-error"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                  placeholder="Deskripsi satuan..."></textarea>
                        <span class="text-sm text-red-600 error-message" id="description-error"></span>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" id="submit-btn" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Satuan
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
    
    <!-- Daftar Satuan -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Satuan</h3>
                        <p class="text-sm text-gray-500 mt-1">Total <span id="total-satuan">{{ $totalSatuan }}</span> satuan terdaftar</p>
                    </div>
                    <div class="relative">
                        <input type="text" id="search-satuan" 
                               class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm"
                               placeholder="Cari satuan...">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div id="satuan-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($satuans as $satuan)
                    <div class="satuan-card border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200" 
                         data-id="{{ $satuan->id }}"
                         data-name="{{ $satuan->name }}"
                         data-code="{{ $satuan->code }}"
                         data-description="{{ $satuan->description }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 text-lg">{{ $satuan->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $satuan->description ?: '-' }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full font-medium">
                                        <i class="fas fa-tag mr-1"></i> {{ $satuan->code }}
                                    </span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $satuan->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex space-x-1 ml-4">
                                <button class="edit-btn p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition-colors" 
                                        data-id="{{ $satuan->id }}" 
                                        data-name="{{ $satuan->name }}"
                                        data-code="{{ $satuan->code }}"
                                        data-description="{{ $satuan->description }}"
                                        title="Edit Satuan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-btn p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors" 
                                        data-id="{{ $satuan->id }}" 
                                        data-name="{{ $satuan->name }}"
                                        title="Hapus Satuan">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-8 text-gray-500" id="empty-state">
                        <i class="fas fa-box-open text-5xl mb-3 opacity-50"></i>
                        <p class="text-lg font-medium">Belum ada data satuan</p>
                        <p class="text-sm mt-1">Silakan tambahkan satuan baru melalui form di samping</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.satuan-card {
    transition: all 0.3s ease;
}

.satuan-card:hover {
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
    
    // Fungsi reset form
    function resetForm() {
        $('#satuan-form')[0].reset();
        $('#satuan-id').val('');
        $('#form-title').text('Tambah Satuan');
        $('#submit-btn').html('<i class="fas fa-save mr-2"></i> Simpan Satuan');
        $('#cancel-btn').addClass('hidden');
        $('.error-message').text('');
        $('input, textarea').removeClass('border-red-500');
    }
    
    // Fungsi update total count
    function updateTotalCount() {
        let total = $('.satuan-card').length;
        $('#total-satuan').text(total);
        if (total === 0 && $('#empty-state').length === 0) {
            $('#satuan-list').html(`
                <div class="col-span-2 text-center py-8 text-gray-500" id="empty-state">
                    <i class="fas fa-box-open text-5xl mb-3 opacity-50"></i>
                    <p class="text-lg font-medium">Belum ada data satuan</p>
                    <p class="text-sm mt-1">Silakan tambahkan satuan baru melalui form di samping</p>
                </div>
            `);
        }
    }
    
    // ========== FORM SUBMIT (TAMBAH/UPDATE) ==========
    $('#satuan-form').on('submit', function(e) {
        e.preventDefault();
        
        let id = $('#satuan-id').val();
        let url = id ? '/admin/satuan/' + id : '/admin/satuan';
        let method = id ? 'PUT' : 'POST';
        
        // Hapus pesan error sebelumnya
        $('.error-message').text('');
        $('input, textarea').removeClass('border-red-500');
        
        // Nonaktifkan tombol submit
        let submitBtn = $('#submit-btn');
        let originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
        
        $.ajax({
            url: url,
            method: method,
            data: {
                name: $('#name').val(),
                code: $('#code').val(),
                description: $('#description').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
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
    
    // ========== EDIT SATUAN ==========
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let code = $(this).data('code');
        let description = $(this).data('description');
        
        // Isi form dengan data satuan
        $('#satuan-id').val(id);
        $('#name').val(name);
        $('#code').val(code);
        $('#description').val(description || '');
        
        // Ubah tampilan form
        $('#form-title').text('Edit Satuan');
        $('#submit-btn').html('<i class="fas fa-save mr-2"></i> Update Satuan');
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
            text: `Sedang mengedit satuan: ${name}`,
            timer: 2000,
            showConfirmButton: false
        });
    });
    
    // ========== HAPUS SATUAN ==========
    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        let satuanName = $(this).data('name');
        let card = $(this).closest('.satuan-card');
        
        Swal.fire({
            title: 'Hapus Satuan',
            html: `
                <div class="text-left">
                    <p>Apakah Anda yakin ingin menghapus satuan:</p>
                    <p class="font-bold text-red-600 my-2">"${satuanName}"</p>
                    <p class="text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '/admin/satuan/' + id,
                    method: 'DELETE',
                    data: {
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
        });
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
                    text: 'Edit satuan dibatalkan',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });
    
    // ========== SEARCH SATUAN ==========
    $('#search-satuan').on('keyup', function() {
        let searchTerm = $(this).val().toLowerCase();
        
        $('.satuan-card').each(function() {
            let name = $(this).data('name').toLowerCase();
            let code = $(this).data('code').toLowerCase();
            
            if (name.includes(searchTerm) || code.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Tampilkan pesan jika tidak ada hasil
        let visibleCards = $('.satuan-card:visible').length;
        if (visibleCards === 0 && $('.satuan-card').length > 0) {
            if ($('#no-search-result').length === 0) {
                $('#satuan-list').append(`
                    <div id="no-search-result" class="col-span-2 text-center py-8 text-gray-500">
                        <i class="fas fa-search text-4xl mb-3 opacity-50"></i>
                        <p>Tidak ada satuan yang ditemukan</p>
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
    
    console.log('Satuan page initialized');
});
</script>
@endpush
@endsection