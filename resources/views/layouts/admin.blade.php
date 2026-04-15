<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - SIPBHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        .sidebar-wrapper {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 16rem;
            z-index: 40;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #4b5563 #1e293b;
            transition: none; /* Mencegah flicker saat load */
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 20px;
        }
        
        .main-content {
            margin-left: 16rem;
            width: calc(100% - 16rem);
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
        }
        
        .content-wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .content-header {
            position: sticky;
            top: 0;
            z-index: 30;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .content-area {
            flex: 1;
            padding: 1.5rem;
        }
        
        .nav-link {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
        }
        
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            margin-top: 0;
            will-change: max-height; /* Optimasi performa */
        }
        
        .dropdown-menu.open {
            max-height: 1000px;
            transition: max-height 0.4s ease-in;
        }
        
        .dropdown-toggle {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }
        
        .dropdown-toggle i.fa-chevron-down,
        .dropdown-toggle i.fa-chevron-up {
            transition: transform 0.3s ease;
        }
        
        .dropdown-toggle.active i.fa-chevron-down {
            transform: rotate(180deg);
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .stat-card {
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
        }
        
        #notificationsDropdown {
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform-origin: top right;
        }
        
        #notificationsDropdown:not(.hidden) {
            animation: slideDown 0.2s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .main-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .main-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .main-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .no-select {
            user-select: none;
        }
        
        #realTimeClock {
            background: #f8fafc;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        #clockDate {
            color: #4b5563;
            margin-right: 0.5rem;
        }
        
        #clockTime {
            color: #1e293b;
            font-weight: 600;
        }
        
        @keyframes timeUpdate {
            0% { opacity: 0.5; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .time-updated {
            animation: timeUpdate 0.3s ease;
        }
        
        .notification-toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1000;
            animation: slideInRight 0.3s ease;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>
    
    <div class="sidebar-wrapper">
        <div class="sidebar text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SIPBHP</h1>
                        <p class="text-xs text-blue-200">Admin Dashboard</p>
                    </div>
                </div>
                
                <div class="mb-8 p-4 bg-white/10 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ Auth::user()->nama ?? 'Admin' }}</h3>
                            <p class="text-sm text-blue-200">{{ Auth::user()->bidang ?? 'Administrator' }}</p>
                            <p class="text-xs text-gray-300">{{ Auth::user()->nip ?? 'NIP: -' }}</p>
                        </div>
                    </div>
                </div>
                
                <nav class="space-y-1 pb-6" id="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       data-url="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <div class="dropdown-item">
                        <div class="dropdown-toggle nav-link flex items-center justify-between p-3 rounded-lg" data-dropdown="masterData">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-database w-5"></i>
                                <span>Master Data</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                        <div class="dropdown-menu ml-4" id="masterData">
                            <a href="{{ route('admin.barang.index') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-6 rounded-lg {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}"
                               data-url="{{ route('admin.barang.index') }}">
                                <i class="fas fa-box w-5 text-center"></i>
                                <span class="whitespace-nowrap text-sm">Master Barang</span>
                            </a>
                            <a href="{{ route('admin.satuan.index') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-6 rounded-lg {{ request()->routeIs('admin.satuan.*') ? 'active' : '' }}"
                               data-url="{{ route('admin.satuan.index') }}">
                                <i class="fas fa-balance-scale w-5 text-center"></i>
                                <span class="whitespace-nowrap text-sm">Satuan Barang</span>
                            </a>
                            <a href="{{ route('admin.kategori.index') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-6 rounded-lg {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}"
                               data-url="{{ route('admin.kategori.index') }}">
                                <i class="fas fa-tags w-5 text-center"></i>
                                <span class="whitespace-nowrap text-sm">Kategori Barang</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="dropdown-item">
                        <div class="dropdown-toggle nav-link flex items-center justify-between p-3 rounded-lg" data-dropdown="transaksi">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-exchange-alt w-5"></i>
                                <span>Transaksi</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                        <div class="dropdown-menu ml-4" id="transaksi">
                            <a href="{{ route('admin.barang-masuk.index') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.barang-masuk.*') ? 'active' : '' }}"
                               data-url="{{ route('admin.barang-masuk.index') }}">
                                <i class="fas fa-inbox w-5"></i>
                                <span>Barang Masuk</span>
                            </a>
                            <a href="{{ route('admin.barang-keluar.index') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.barang-keluar.*') ? 'active' : '' }}"
                               data-url="{{ route('admin.barang-keluar.index') }}">
                                <i class="fas fa-box-open w-5 text-center"></i>
                                <span>Barang Keluar</span>
                            </a>
                            <a href="{{ route('admin.kartu.persediaan') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.kartu.*') ? 'active' : '' }}"
                               data-url="{{ route('admin.kartu.persediaan') }}">
                                <i class="fas fa-clipboard-list w-5"></i>
                                <span>Kartu Persediaan</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="dropdown-item">
                        <div class="dropdown-toggle nav-link flex items-center justify-between p-3 rounded-lg" data-dropdown="permintaan">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-clipboard-list w-5"></i>
                                <span>Permintaan</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                        <div class="dropdown-menu ml-4" id="permintaan">
                            <a href="{{ route('admin.permintaan.index') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.permintaan.index') ? 'active' : '' }}"
                               data-url="{{ route('admin.permintaan.index') }}">
                                <i class="fas fa-clipboard-check w-5"></i>
                                <span>Daftar Permintaan</span>
                                @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="ml-auto px-2 py-1 text-xs bg-red-500 rounded-full">{{ $pendingCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.permintaan.menunggu') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.permintaan.menunggu') ? 'active' : '' }}"
                               data-url="{{ route('admin.permintaan.menunggu') }}">
                                <i class="fas fa-clock w-5"></i>
                                <span>Menunggu Persetujuan</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="dropdown-item">
                        <div class="dropdown-toggle nav-link flex items-center justify-between p-3 rounded-lg" data-dropdown="manajemen">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-cog w-5"></i>
                                <span>Manajemen</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                        <div class="dropdown-menu ml-4" id="manajemen">
                            <a href="{{ route('admin.manajemen-pengguna') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.manajemen-pengguna') ? 'active' : '' }}"
                               data-url="{{ route('admin.manajemen-pengguna') }}">
                                <i class="fas fa-users w-5"></i>
                                <span>Manajemen Pengguna</span>
                            </a>
                            <a href="{{ route('admin.manajemen-barang') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.manajemen-barang') ? 'active' : '' }}"
                               data-url="{{ route('admin.manajemen-barang') }}">
                                <i class="fas fa-boxes w-5"></i>
                                <span>Manajemen Barang</span>
                            </a>
                        </div>
                    </div>
                    
                               
                    
                    
                    <div class="dropdown-item">
                        <div class="dropdown-toggle nav-link flex items-center justify-between p-3 rounded-lg" data-dropdown="pengaturan">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-sliders-h w-5"></i>
                                <span>Pengaturan</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                        <div class="dropdown-menu ml-4" id="pengaturan">
                            <a href="{{ route('admin.profile') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
                               data-url="{{ route('admin.profile') }}">
                                <i class="fas fa-user-cog w-5"></i>
                                <span>Profil Admin</span>
                            </a>
                            <a href="{{ route('admin.settings') }}" 
                               class="nav-link flex items-center space-x-3 p-3 pl-8 rounded-lg {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                               data-url="{{ route('admin.settings') }}">
                                <i class="fas fa-cog w-5"></i>
                                <span>Pengaturan Sistem</span>
                            </a>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="nav-link flex items-center space-x-3 p-3 rounded-lg w-full text-left hover:bg-red-500/10 hover:border-red-500 mt-4">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="content-wrapper">
            <header class="content-header bg-white px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900" id="pageTitle">@yield('page-title', 'Dashboard Admin')</h2>
                        <p class="text-gray-600" id="pageSubtitle">@yield('page-subtitle', 'Selamat datang, ' . (Auth::user()->nama ?? 'Admin'))</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            <i class="fas fa-shield-alt mr-1"></i>Admin
                        </span>
                        <div id="realTimeClock">
                            <i class="far fa-clock mr-2"></i>
                            <span id="clockDate"></span>
                            <span id="clockTime"></span>
                        </div>
                        <button onclick="window.toggleNotifications()" class="relative p-2 hover:bg-gray-100 rounded-full">
                            <i class="fas fa-bell text-gray-600"></i>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 rounded-full text-white text-[10px] font-bold flex items-center justify-center">{{ Auth::user()->unreadNotifications->count() }}</span>
                            @endif
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3" id="breadcrumb">
                            <li class="inline-flex items-center">
                                <a href="#" onclick="window.loadContent('{{ route('admin.dashboard') }}'); return false;" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-home mr-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            @hasSection('breadcrumb')
                                @yield('breadcrumb')
                            @endif
                        </ol>
                    </nav>
                </div>
            </header>
            
            <div id="notificationsDropdown" class="hidden fixed right-6 top-20 w-80 bg-white rounded-lg shadow-lg border z-50">
                <div class="p-4 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">Notifikasi</h3>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-blue-600 text-sm hover:underline">Tandai semua telah dibaca</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse(Auth::user()->unreadNotifications as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-4 border-b hover:bg-gray-50 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/30' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-100' }}">
                                    <i class="fas fa-bell {{ $notification->read_at ? 'text-gray-500' : 'text-blue-600' }} text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->data['pengaju'] ?? 'Sistem' }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ collect($notification->data)->get('pesan', 'Ada notifikasi baru') }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-4xl mb-3 text-gray-300"></i>
                        <p class="text-sm">Tidak ada notifikasi baru</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <main class="content-area" id="mainContent">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    (function() {
        'use strict';
        
        if (window.SIBHPInitialized) {
            return;
        }
        window.SIBHPInitialized = true;
        
        // ========== VARIABLES ==========
        window.sidebarScrollPosition = localStorage.getItem('sidebarScrollPosition') || 0;
        window.currentPageUrl = window.location.pathname;
        
        // ========== HELPER FUNCTIONS ==========
        window.saveSidebarScroll = function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                window.sidebarScrollPosition = sidebar.scrollTop;
                localStorage.setItem('sidebarScrollPosition', window.sidebarScrollPosition);
            }
        };
        
        window.restoreSidebarScroll = function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                const savedPosition = localStorage.getItem('sidebarScrollPosition');
                if (savedPosition !== null) {
                    sidebar.scrollTop = parseInt(savedPosition);
                }
            }
        };
        
        window.showToast = function(type, message) {
            const toast = document.createElement('div');
            let bgColor = '';
            let icon = '';
            
            switch(type) {
                case 'success':
                    bgColor = 'bg-green-500';
                    icon = 'fa-check-circle';
                    break;
                case 'error':
                    bgColor = 'bg-red-500';
                    icon = 'fa-exclamation-circle';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-500';
                    icon = 'fa-exclamation-triangle';
                    break;
                default:
                    bgColor = 'bg-blue-500';
                    icon = 'fa-info-circle';
            }
            
            toast.className = `notification-toast ${bgColor} text-white px-4 py-3 rounded-lg shadow-lg`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };
        
        window.cleanupModals = function() {
            const modals = document.querySelectorAll('.fixed.inset-0.bg-black.bg-opacity-50');
            modals.forEach(modal => {
                if (modal && modal.parentNode) {
                    modal.parentNode.removeChild(modal);
                }
            });
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        };
        
        // Removed saveDropdownState and restoreDropdownState since they could cause all dropdowns to open upon login
        
        // ========== LOAD CONTENT FUNCTION ==========
        window.loadContent = function(url, pushState = true) {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) loadingOverlay.classList.add('active');
            
            // Simpan state sidebar SEBELUM load
            const sidebar = document.querySelector('.sidebar');
            const savedSidebarScroll = sidebar ? sidebar.scrollTop : 0;
            
            // Simpan dropdown state yang sedang terbuka
            const openDropdowns = [];
            document.querySelectorAll('.dropdown-menu.open').forEach(menu => {
                openDropdowns.push(menu.id);
            });
            
            const mainContent = document.querySelector('.main-content');
            const currentScrollPosition = mainContent ? mainContent.scrollTop : 0;
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP error: ' + response.status);
                return response.text();
            })
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Cari konten utama
                let mainContentSource = tempDiv.querySelector('#mainContent');
                if (!mainContentSource) mainContentSource = tempDiv.querySelector('.content-area');
                if (!mainContentSource) mainContentSource = tempDiv.querySelector('main');
                
                if (!mainContentSource) {
                    window.location.href = url;
                    return;
                }
                
                const newContent = mainContentSource.innerHTML;
                const newTitle = tempDiv.querySelector('title')?.innerText || 'SIBHP';
                const newPageTitle = tempDiv.querySelector('#pageTitle')?.innerHTML;
                const newPageSubtitle = tempDiv.querySelector('#pageSubtitle')?.innerHTML;
                const newBreadcrumb = tempDiv.querySelector('#breadcrumb')?.innerHTML;
                
                // Update konten utama SAJA
                const mainContentEl = document.getElementById('mainContent');
                if (mainContentEl) {
                    mainContentEl.innerHTML = newContent;
                }
                
                document.title = newTitle;
                
                const pageTitleEl = document.getElementById('pageTitle');
                const pageSubtitleEl = document.getElementById('pageSubtitle');
                const breadcrumbEl = document.getElementById('breadcrumb');
                
                if (pageTitleEl && newPageTitle) pageTitleEl.innerHTML = newPageTitle;
                if (pageSubtitleEl && newPageSubtitle) pageSubtitleEl.innerHTML = newPageSubtitle;
                if (breadcrumbEl && newBreadcrumb) breadcrumbEl.innerHTML = newBreadcrumb;
                
                // Restore scroll position
                if (mainContent) mainContent.scrollTop = currentScrollPosition;
                
                // Update URL
                if (pushState) {
                    window.history.pushState({ url: url }, '', url);
                }
                
                // Update active menu tanpa reset sidebar
                window.updateActiveMenu(url);
                
                // RESTORE SIDEBAR STATE
                setTimeout(() => {
                    // Restore sidebar scroll position
                    if (sidebar) {
                        sidebar.scrollTop = savedSidebarScroll;
                    }
                    
                    // Restore active menu's parent dropdown
                    const currentActiveLink = document.querySelector('.dropdown-menu .nav-link.active');
                    if (currentActiveLink) {
                        let parentDropdown = currentActiveLink.closest('.dropdown-menu');
                        if (parentDropdown) {
                            let dropdownId = parentDropdown.id;
                            let toggleButton = document.querySelector(`[data-dropdown="${dropdownId}"]`);
                            if (toggleButton && !parentDropdown.classList.contains('open')) {
                                parentDropdown.style.transition = 'none';
                                parentDropdown.classList.add('open');
                                toggleButton.classList.add('active');
                                const icon = toggleButton.querySelector('.fa-chevron-down, .fa-chevron-up');
                                if (icon) {
                                    icon.classList.remove('fa-chevron-down');
                                    icon.classList.add('fa-chevron-up');
                                }
                                setTimeout(() => {
                                    parentDropdown.style.transition = '';
                                }, 100);
                            }
                        }
                    }
                    
                    // Update navigation links (tapi JANGAN reset dropdown toggles)
                    window.updateNavLinks();
                    
                    // Reset flags untuk re-initialization
                    window._barangMasukInitialized = false;
                    window._barangPageInitialized = false;
                    window._kategoriPageInitialized = false;
                    
                    // Remove old dynamic scripts to prevent memory leaks or duplicate execution
                    document.querySelectorAll('script[data-dynamic="true"]').forEach(el => el.remove());
                    
                    // Eksekusi script dari halaman baru
                    const scripts = tempDiv.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        try {
                            if (oldScript.src && /tailwind|font-awesome|jquery|sweetalert/i.test(oldScript.src)) {
                                return;
                            }
                            
                            if (oldScript.src) {
                                const newScript = document.createElement('script');
                                newScript.src = oldScript.src;
                                newScript.async = false;
                                newScript.setAttribute('data-dynamic', 'true');
                                document.body.appendChild(newScript);
                                return;
                            }
                            
                            let scriptContent = oldScript.textContent;
                            if (!scriptContent || !scriptContent.trim()) return;
                            
                            try {
                                const newInlineScript = document.createElement('script');
                                newInlineScript.textContent = scriptContent;
                                newInlineScript.setAttribute('data-dynamic', 'true');
                                document.body.appendChild(newInlineScript);
                            } catch (e) {
                                console.warn('Error executing inline script:', e);
                            }
                        } catch (e) {
                            console.warn('Error processing script:', e);
                        }
                    });
                    
                    // Tambahkan style dari halaman baru
                    tempDiv.querySelectorAll('style').forEach(style => {
                        if (style.textContent) {
                            const newStyle = document.createElement('style');
                            newStyle.textContent = style.textContent;
                            newStyle.setAttribute('data-dynamic', 'true');
                            document.head.appendChild(newStyle);
                        }
                    });
                    
                    window.cleanupModals();
                    document.dispatchEvent(new CustomEvent('contentLoaded', { detail: { url: url } }));
                    
                    if (loadingOverlay) loadingOverlay.classList.remove('active');
                    console.log('Content loaded successfully:', url);
                }, 150);
            })
            .catch(error => {
                console.error('Error loading content:', error);
                if (loadingOverlay) loadingOverlay.classList.remove('active');
                window.location.href = url;
            });
        };
        
        // ========== NAVIGATION FUNCTIONS ==========
        window.updateActiveMenu = function(url) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            const links = document.querySelectorAll(`.nav-link[data-url="${url}"]`);
            links.forEach(link => {
                link.classList.add('active');
                
                let parentDropdown = link.closest('.dropdown-menu');
                if (parentDropdown) {
                    let dropdownId = parentDropdown.id;
                    let toggleButton = document.querySelector(`[data-dropdown="${dropdownId}"]`);
                    if (toggleButton && !parentDropdown.classList.contains('open')) {
                        window.openDropdown(toggleButton, parentDropdown);
                    }
                }
            });
        };
        
        window.updateNavLinks = function() {
            const currentUrl = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                const linkUrl = link.getAttribute('data-url');
                if (linkUrl && (currentUrl === linkUrl || currentUrl.startsWith(linkUrl + '/'))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        };
        
        window.initDropdowns = function() {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.removeEventListener('click', window.handleDropdownClick);
                toggle.addEventListener('click', window.handleDropdownClick);
            });
        };
        
        window.handleDropdownClick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdownId = this.getAttribute('data-dropdown');
            const dropdownMenu = document.getElementById(dropdownId);
            if (dropdownMenu) {
                if (dropdownMenu.classList.contains('open')) {
                    window.closeDropdown(this, dropdownMenu);
                } else {
                    window.openDropdown(this, dropdownMenu);
                }
                window.saveSidebarScroll();
            }
        };
        
        window.openDropdown = function(toggle, menu) {
            menu.classList.add('open');
            toggle.classList.add('active');
            const icon = toggle.querySelector('.fa-chevron-down, .fa-chevron-up');
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        };
        
        window.closeDropdown = function(toggle, menu) {
            menu.classList.remove('open');
            toggle.classList.remove('active');
            const icon = toggle.querySelector('.fa-chevron-down, .fa-chevron-up');
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        };
        
        window.initNavigation = function() {
            const navLinks = document.querySelectorAll('.nav-link[data-url]');
            navLinks.forEach(link => {
                link.removeEventListener('click', window.handleNavClick);
                link.addEventListener('click', window.handleNavClick);
            });
        };
        
        window.handleNavClick = function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            if (url) {
                window.loadContent(url);
            }
        };
        
        window.initPopState = function() {
            window.addEventListener('popstate', function(event) {
                if (event.state && event.state.url) {
                    window.loadContent(event.state.url, false);
                } else {
                    location.reload();
                }
            });
        };
        
        window.toggleNotifications = function() {
            const dropdown = document.getElementById('notificationsDropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        };
        
        // ========== CLOCK FUNCTIONS ==========
        let clockInterval = null;
        
        window.updateRealTimeClock = function() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dateString = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            const timeString = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
            
            const clockDateElement = document.getElementById('clockDate');
            const clockTimeElement = document.getElementById('clockTime');
            
            if (clockDateElement && clockTimeElement) {
                clockDateElement.textContent = dateString;
                clockTimeElement.textContent = timeString;
                clockTimeElement.classList.add('time-updated');
                setTimeout(() => clockTimeElement.classList.remove('time-updated'), 300);
            }
        };
        
        window.initRealTimeClock = function() {
            if (clockInterval) clearInterval(clockInterval);
            window.updateRealTimeClock();
            clockInterval = setInterval(window.updateRealTimeClock, 1000);
        };
        
        window.stopRealTimeClock = function() {
            if (clockInterval) {
                clearInterval(clockInterval);
                clockInterval = null;
            }
        };
        
        // ========== INITIALIZATION ==========
        function init() {
            window.initDropdowns();
            window.initNavigation();
            window.initPopState();
            window.restoreSidebarScroll();
            window.initRealTimeClock();
            
            // Auto open the active menu's parent dropdown
            const currentActiveLink = document.querySelector('.dropdown-menu .nav-link.active');
            if (currentActiveLink) {
                let parentDropdown = currentActiveLink.closest('.dropdown-menu');
                if (parentDropdown) {
                    let dropdownId = parentDropdown.id;
                    let toggleButton = document.querySelector(`[data-dropdown="${dropdownId}"]`);
                    if (toggleButton && !parentDropdown.classList.contains('open')) {
                        parentDropdown.style.transition = 'none';
                        parentDropdown.classList.add('open');
                        toggleButton.classList.add('active');
                        const icon = toggleButton.querySelector('.fa-chevron-down, .fa-chevron-up');
                        if (icon) {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        }
                        setTimeout(() => {
                            parentDropdown.style.transition = '';
                        }, 100);
                    }
                }
            }
            
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.addEventListener('scroll', window.saveSidebarScroll);
            }
            
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.addEventListener('scroll', function() {
                    localStorage.setItem('mainContentScrollPosition', mainContent.scrollTop);
                });
                
                const savedMainScroll = localStorage.getItem('mainContentScrollPosition');
                if (savedMainScroll) {
                    mainContent.scrollTop = parseInt(savedMainScroll);
                }
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
        
        window.addEventListener('beforeunload', window.stopRealTimeClock);
    })();
    </script>
    
    <script>
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationsDropdown');
        const button = event.target.closest('button[onclick*="toggleNotifications"]');
        
        if (dropdown && !dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.mb-6.bg-emerald-50, .mb-6.bg-red-50');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
    </script>
    
    @stack('scripts')
</body>
</html>
