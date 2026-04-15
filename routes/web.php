<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\BarangMasukController;
use App\Http\Controllers\User\PermintaanController as UserPermintaanController;
use App\Http\Controllers\Admin\PermintaanController as AdminPermintaanController;
use App\Http\Controllers\Admin\BarangKeluarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ========== AUTH ROUTES ==========
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart-data', [AdminController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    
    // User Management
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::get('/manajemen-pengguna', [AdminController::class, 'manajemenPengguna'])->name('manajemen-pengguna');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // ========== RESOURCE SATUAN ==========
   Route::get('satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::post('satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('satuan/{id}', [SatuanController::class, 'show'])->name('satuan.show');
    Route::put('satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
    Route::get('satuan-options', [SatuanController::class, 'getOptions'])->name('satuan.options');

    // ========== RESOURCE KATEGORI ==========
    Route::resource('kategori', KategoriController::class)->except(['create', 'edit']);
    Route::get('kategori-options', [KategoriController::class, 'getOptions'])->name('kategori.options');
    
    // ========== RESOURCE BARANG ==========
    Route::resource('barang', BarangController::class)->except(['create', 'edit']);
    Route::get('barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::post('barang/check-kode', [BarangController::class, 'checkKode'])->name('barang.check-kode');
    
    // ========== RESOURCE BARANG MASUK ==========
     Route::get('barang-masuk/print', [BarangMasukController::class, 'print'])->name('barang-masuk.print');
    Route::resource('barang-masuk', BarangMasukController::class);
    Route::get('barang-masuk/export', [BarangMasukController::class, 'export'])->name('barang-masuk.export');
    Route::get('laporan/barang-masuk', [BarangMasukController::class, 'laporan'])->name('laporan.barang-masuk');
    
    

    Route::get('/kartu-persediaan', [AdminController::class, 'kartuPersediaan'])->name('kartu.persediaan');
    Route::get('/kartu-persediaan/print', [AdminController::class, 'kartuPersediaanPrint'])->name('kartu.persediaan.print');
    
    // Permintaan & Persetujuan
    Route::get('/permintaan', [AdminPermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/menunggu', [AdminPermintaanController::class, 'menunggu'])->name('permintaan.menunggu');
    Route::get('/permintaan/{id}', [AdminPermintaanController::class, 'show'])->name('permintaan.show');
    Route::get('/permintaan/{id}/detail-json', [AdminPermintaanController::class, 'getDetailJson']);
    Route::post('/permintaan/{id}/approve', [AdminPermintaanController::class, 'approve'])->name('permintaan.approve');
    Route::post('/permintaan/{id}/reject', [AdminPermintaanController::class, 'reject'])->name('permintaan.reject');
    Route::get('/permintaan/{id}/cetak-struk', [AdminPermintaanController::class, 'cetakStruk'])->name('permintaan.cetak-struk');
    
   // Barang Keluar Routes
Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
Route::get('/barang-keluar/print', [BarangKeluarController::class, 'print'])->name('barang-keluar.print'); // <-- route ini
Route::get('/barang-keluar/{id}', [BarangKeluarController::class, 'show'])->name('barang-keluar.show');
Route::get('/barang-keluar/{id}/edit', [BarangKeluarController::class, 'edit'])->name('barang-keluar.edit');
Route::put('/barang-keluar/{id}', [BarangKeluarController::class, 'update'])->name('barang-keluar.update');
Route::delete('/barang-keluar/{id}', [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');
Route::get('/barang-keluar/export', [BarangKeluarController::class, 'export'])->name('barang-keluar.export');
Route::get('/barang-keluar/report', [BarangKeluarController::class, 'report'])->name('barang-keluar.report');


    // Manajemen
    Route::get('/manajemen-barang', [AdminController::class, 'manajemenBarang'])->name('manajemen-barang');
    Route::get('/manajemen-barang/export', [AdminController::class, 'exportBarangCsv'])->name('manajemen-barang.export');
    Route::get('/manajemen-barang/template', [AdminController::class, 'templateBarangCsv'])->name('manajemen-barang.template');
    Route::post('/manajemen-barang/import', [AdminController::class, 'importBarangCsv'])->name('manajemen-barang.import');
    Route::get('/divisi', [AdminController::class, 'divisiIndex'])->name('divisi.index');
    
    // Dokumen & Laporan
    Route::get('/dokumen/spb', [AdminController::class, 'dokumenSPB'])->name('dokumen.spb');
    Route::get('/dokumen/bast', [AdminController::class, 'dokumenBAST'])->name('dokumen.bast');
    Route::get('/dokumen/sppb', [AdminController::class, 'dokumenSPPB'])->name('dokumen.sppb');
    Route::get('/laporan', [AdminController::class, 'laporanIndex'])->name('laporan');
    
    // Pengaturan
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// ========== USER ROUTES ==========
Route::middleware(['auth', 'role:pengguna'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Permintaan routes
    Route::get('/permintaan', [UserPermintaanController::class, 'index'])->name('permintaan.riwayat');
    Route::get('/permintaan/create', [UserPermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('/permintaan', [UserPermintaanController::class, 'store'])->name('permintaan.store');
    Route::get('/permintaan/{id}', [UserPermintaanController::class, 'show'])->name('permintaan.show');
    Route::get('/permintaan/{id}/detail-json', [UserPermintaanController::class, 'getDetailJson']);
    Route::post('/permintaan/{id}/approve', [UserPermintaanController::class, 'approveUser'])->name('permintaan.approve');
    Route::get('/permintaan/{id}/cetak-struk', [UserPermintaanController::class, 'cetakStruk'])->name('permintaan.cetak-struk');
});

// ========== COMMON ROUTES ==========
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::post('/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.mark-all-read');
});