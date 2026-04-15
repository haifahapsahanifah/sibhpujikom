<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Barang Habis Pakai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --accent-blue: #60a5fa;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
        }
        
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }
            50% { box-shadow: 0 0 30px rgba(59, 130, 246, 0.3); }
        }
        
        .wave-animation {
            animation: wave 4s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
                line-height: 1.2;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
        
        @media (max-width: 1024px) {
            .timeline-desktop {
                display: none;
            }
            
            .timeline-mobile {
                display: block;
            }
        }
        
        @media (min-width: 1024px) {
            .timeline-desktop {
                display: block;
            }
            
            .timeline-mobile {
                display: none;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300 shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-blue-500 rounded-lg flex items-center justify-center pulse-glow">
                        <i class="fas fa-boxes text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">SIPBHP</h1>
                        <p class="text-xs text-gray-500 hidden sm:block">Sistem Informasi Pengajuan Barang Habis Pakai</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#beranda" class="group relative text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <span>Beranda</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#fitur" class="group relative text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <span>Fitur</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#alur" class="group relative text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <span>Alur Sistem</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#kontak" class="group relative text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <span>Kontak</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                    </a>
                </div>

                <!-- CTA Button -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition-all shadow hover:shadow-md hover-lift">
                        <i class="fas fa-sign-in-alt text-xs"></i>
                        <span>Masuk Sistem</span>
                    </a>
                    <button id="menu-toggle" class="lg:hidden text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-black/50 z-50 hidden lg:hidden" onclick="closeMobileMenu()">
        <div class="absolute top-0 right-0 h-full w-64 bg-white shadow-xl transform transition-transform duration-300" onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex justify-between items-center mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-500 rounded flex items-center justify-center">
                            <i class="fas fa-boxes text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">SIPBHP</h3>
                            <p class="text-xs text-gray-500">Sistem Informasi Pengajuan Barang Habis Pakai</p>
                        </div>
                    </div>
                    <button onclick="closeMobileMenu()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div class="space-y-2">
                    <a href="#beranda" onclick="closeMobileMenu()" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 transition-colors">
                        <i class="fas fa-home text-blue-600 w-4"></i>
                        <span class="font-medium">Beranda</span>
                    </a>
                    <a href="#fitur" onclick="closeMobileMenu()" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 transition-colors">
                        <i class="fas fa-star text-blue-600 w-4"></i>
                        <span class="font-medium">Fitur</span>
                    </a>
                    <a href="#alur" onclick="closeMobileMenu()" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 transition-colors">
                        <i class="fas fa-sitemap text-blue-600 w-4"></i>
                        <span class="font-medium">Alur Sistem</span>
                    </a>
                    <a href="#kontak" onclick="closeMobileMenu()" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 transition-colors">
                        <i class="fas fa-envelope text-blue-600 w-4"></i>
                        <span class="font-medium">Kontak</span>
                    </a>
                </div>
                
                <div class="mt-8 pt-6 border-t">
                    <a href="{{ route('login') }}" onclick="closeMobileMenu()" class="flex items-center justify-center space-x-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-medium py-3 rounded-lg hover:bg-gradient-to-r hover:from-blue-700 hover:to-blue-600 transition-all">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk Sistem</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="beranda" class="pt-28 pb-16 md:pt-32 md:pb-24 relative overflow-hidden gradient-bg">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 translate-x-1/3 translate-y-1/3"></div>
        
        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center">
                <!-- Left Content -->
                <div class="lg:w-1/2 mb-12 lg:mb-0 fade-in">
                    <div class="inline-flex items-center space-x-2 bg-white text-blue-700 px-4 py-2 rounded-full mb-6 shadow-sm">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        <span class="text-sm font-medium">Solusi Digital Terintegrasi</span>
                    </div>
                    
                    <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Sistem Informasi Pengajuan <span class="text-gradient">Barang Habis Pakai</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-xl leading-relaxed">
                        Transformasi digital pengelolaan persediaan dengan sistem terintegrasi yang menjamin akurasi, transparansi, dan efisiensi operasional instansi Anda.
                    </p>
                </div>

                <!-- Right Content -->
                <div class="lg:w-1/2 lg:pl-8 xl:pl-12 mt-12 lg:mt-0">
                    <div class="relative">
                        <!-- Main Illustration Container -->
                        <div class="bg-white rounded-2xl p-6 shadow-xl border border-blue-50 overflow-hidden">
                            <!-- Feature Illustration -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Sistem Terintegrasi</h3>
                                        <p class="text-gray-500">Manajemen Barang Digital</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-green-600 text-sm font-medium">Live</span>
                                    </div>
                                </div>
                                
                                <!-- Feature Icons Grid -->
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-blue-50 rounded-lg p-4 text-center hover-lift">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-box text-blue-600 text-xl"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Inventori</span>
                                    </div>
                                    <div class="bg-emerald-50 rounded-lg p-4 text-center hover-lift">
                                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-exchange-alt text-emerald-600 text-xl"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Transaksi</span>
                                    </div>
                                    <div class="bg-purple-50 rounded-lg p-4 text-center hover-lift">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Laporan</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stats Bar -->
                            <div class="pt-6 border-t border-gray-100">
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Tingkat Adopsi</span>
                                        <span>92%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-400 h-2 rounded-full" style="width: 92%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center floating">
                            <i class="fas fa-cogs text-blue-600"></i>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center wave-animation">
                            <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16 fade-in">
                <div class="inline-flex items-center space-x-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-full mb-4">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="text-sm font-medium">Kemampuan Sistem</span>
                </div>
                <h2 class="section-title text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    <span class="text-gradient">Fitur Unggulan</span>
                </h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Sistem dilengkapi dengan teknologi terbaru untuk pengelolaan barang habis pakai yang efisien dan transparan.
                </p>
            </div>

            <div class="feature-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature 1 -->
                <div class="fade-in bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover-lift">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-blue-600 text-lg wave-animation"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Manajemen Inventori</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Sistem katalog terintegrasi dengan pelacakan stok real-time dan notifikasi otomatis.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="fade-in bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover-lift">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-emerald-600 text-lg wave-animation" style="animation-delay: 0.5s;"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Transaksi Digital</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Pencatatan otomatis untuk barang masuk dan keluar dengan sistem validasi terstruktur.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="fade-in bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover-lift">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-purple-600 text-lg wave-animation" style="animation-delay: 1s;"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Workflow Approval</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Sistem approval multi-level dengan routing otomatis sesuai struktur organisasi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="fade-in bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover-lift">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-amber-600 text-lg wave-animation" style="animation-delay: 1.5s;"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Analitik & Pelaporan</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Dashboard analitik interaktif dengan visualisasi data untuk pengambilan keputusan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="fade-in bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover-lift">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-100 to-red-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-export text-red-600 text-lg wave-animation" style="animation-delay: 2s;"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Dokumen Digital</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Generasi otomatis dokumen resmi dengan template standar dan tanda tangan digital.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="fade-in bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover-lift">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-indigo-600 text-lg wave-animation" style="animation-delay: 2.5s;"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Keamanan & Audit</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Sistem keamanan berlapis dengan audit trail lengkap untuk setiap transaksi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section id="alur" class="py-16 md:py-24 gradient-bg">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16 fade-in">
                <div class="inline-flex items-center space-x-2 bg-white text-blue-700 px-4 py-2 rounded-full mb-4">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="text-sm font-medium">Proses Terstruktur</span>
                </div>
                <h2 class="section-title text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Alur <span class="text-gradient">Operasional</span>
                </h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Proses sistematis yang menjamin pengelolaan barang berjalan terkendali dan terdokumentasi.
                </p>
            </div>

            <!-- Mobile Timeline -->
            <div class="timeline-mobile space-y-6">
                <!-- Step 1 -->
                <div class="fade-in bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck-loading text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">1. Barang Masuk</h3>
                            <p class="text-gray-600">Penerimaan dan validasi barang dengan sistem scanning dan verifikasi kualitas.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="fade-in bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-database text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">2. Update Stok</h3>
                            <p class="text-gray-600">Pencatatan ke sistem dan update real-time persediaan dengan lokasi penyimpanan.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="fade-in bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">3. Permintaan Barang</h3>
                            <p class="text-gray-600">Pengajuan melalui sistem dengan spesifikasi dan alasan kebutuhan yang jelas.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="fade-in bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-double text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">4. Verifikasi & Approval</h3>
                            <p class="text-gray-600">Proses validasi dan persetujuan oleh pihak berwenang sesuai kebijakan instansi.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="fade-in bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-500 to-red-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box-open text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">5. Distribusi</h3>
                            <p class="text-gray-600">Pengeluaran barang dari gudang dengan sistem pencatatan dan penerimaan.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="fade-in bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">6. Dokumentasi</h3>
                            <p class="text-gray-600">Pembuatan laporan dan arsip digital untuk keperluan audit dan evaluasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Timeline -->
            <div class="timeline-desktop hidden lg:block relative">
                <!-- Timeline Line -->
                <div class="absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-blue-200 via-blue-300 to-blue-200 -translate-y-1/2"></div>
                
                <div class="relative flex justify-between">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center w-1/6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-400 rounded-xl flex items-center justify-center mb-4 shadow-lg floating">
                            <i class="fas fa-truck text-white text-lg"></i>
                        </div>
                        <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-blue-50">
                            <h4 class="font-bold text-gray-900 mb-2 text-sm">Barang Masuk</h4>
                            <p class="text-xs text-gray-600">Penerimaan & Validasi</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center w-1/6">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-400 rounded-xl flex items-center justify-center mb-4 shadow-lg floating" style="animation-delay: 0.5s;">
                            <i class="fas fa-database text-white text-lg"></i>
                        </div>
                        <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-emerald-50">
                            <h4 class="font-bold text-gray-900 mb-2 text-sm">Update Stok</h4>
                            <p class="text-xs text-gray-600">Pencatatan Sistem</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center w-1/6">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-400 rounded-xl flex items-center justify-center mb-4 shadow-lg floating" style="animation-delay: 1s;">
                            <i class="fas fa-clipboard text-white text-lg"></i>
                        </div>
                        <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-purple-50">
                            <h4 class="font-bold text-gray-900 mb-2 text-sm">Permintaan</h4>
                            <p class="text-xs text-gray-600">Pengajuan Kebutuhan</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex flex-col items-center w-1/6">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-400 rounded-xl flex items-center justify-center mb-4 shadow-lg floating" style="animation-delay: 1.5s;">
                            <i class="fas fa-check-double text-white text-lg"></i>
                        </div>
                        <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-amber-50">
                            <h4 class="font-bold text-gray-900 mb-2 text-sm">Verifikasi</h4>
                            <p class="text-xs text-gray-600">Validasi & Approval</p>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="flex flex-col items-center w-1/6">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-400 rounded-xl flex items-center justify-center mb-4 shadow-lg floating" style="animation-delay: 2s;">
                            <i class="fas fa-box-open text-white text-lg"></i>
                        </div>
                        <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-red-50">
                            <h4 class="font-bold text-gray-900 mb-2 text-sm">Distribusi</h4>
                            <p class="text-xs text-gray-600">Barang Keluar</p>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="flex flex-col items-center w-1/6">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-400 rounded-xl flex items-center justify-center mb-4 shadow-lg floating" style="animation-delay: 2.5s;">
                            <i class="fas fa-file-alt text-white text-lg"></i>
                        </div>
                        <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-indigo-50">
                            <h4 class="font-bold text-gray-900 mb-2 text-sm">Dokumentasi</h4>
                            <p class="text-xs text-gray-600">Arsip & Laporan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer yang Sudah Disesuaikan -->
    <footer id="kontak" class="bg-gray-900 text-white">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Logo & Description -->
                <div class="space-y-4 fade-in">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">SIPBHP</h2>
                            <p class="text-xs text-blue-300">Sistem Informasi Pengajuan Barang Habis Pakai</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Sistem untuk mengelola barang habis pakai secara efisien dan terintegrasi.
                    </p>
                </div>
                
                <!-- Quick Links (Menu Cepat) -->
                <div class="fade-in">
                    <h3 class="text-lg font-semibold mb-4 text-blue-300">Menu Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#beranda" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Dashboard Admin</a></li>
                        <li><a href="#fitur" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Manajemen Pengguna</a></li>
                        <li><a href="#alur" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Manajemen Barang</a></li>
                        <li><a href="#kontak" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Laporan</a></li>
                        <li class="pt-2">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">
                                <i class="fas fa-user-circle mr-2"></i>Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="fade-in">
                    <h3 class="text-lg font-semibold mb-4 text-blue-300">Kontak Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                            <span class="text-gray-400 text-sm">Jl. Raya Cisarua No. 123, Loby SMKN 1 Cisarua, Bandung Barat</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-blue-400"></i>
                            <span class="text-gray-400">(022) 1234-5678</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fab fa-whatsapp text-blue-400"></i>
                            <span class="text-gray-400">+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <a href="mailto:sibhp@gmail.com" class="text-gray-400 hover:text-white transition-colors">sibhp@gmail.com</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-clock text-blue-400"></i>
                            <span class="text-gray-400">Senin - Jumat, 08:00 - 16:00</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Divider & Copyright -->
            <div class="border-t border-gray-800 mt-10 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-gray-500 text-sm">
                            &copy; <span id="current-year"></span> SIPBHP - Sistem Informasi Pengajuan Barang Habis Pakai.
                        </p>
                        <p class="text-gray-600 text-xs mt-1">
                            Hak Cipta Dilindungi. All rights reserved.
                        </p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Syarat & Ketentuan</a>
                        <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Bantuan</a>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-gray-600 text-xs">
                            Dibangun dengan 
                            <i class="fas fa-heart text-red-500 mx-1"></i> 
                            menggunakan Laravel & Tailwind CSS
                        </p>
                        <p class="text-gray-700 text-xs mt-1">
                            Version 1.0.0
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Set current year in footer
        document.getElementById('current-year').textContent = new Date().getFullYear();

        // Mobile Menu Functions
        function openMobileMenu() {
            document.getElementById('mobile-menu').classList.remove('hidden');
        }

        function closeMobileMenu() {
            document.getElementById('mobile-menu').classList.add('hidden');
        }

        document.getElementById('menu-toggle').addEventListener('click', openMobileMenu);

        // Close mobile menu when clicking outside
        document.getElementById('mobile-menu').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMobileMenu();
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    closeMobileMenu();
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md');
                nav.classList.remove('bg-white/95');
                nav.classList.add('bg-white');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.add('bg-white/95');
                nav.classList.remove('bg-white');
            }
        });

        // Fade-in animation on scroll
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        fadeElements.forEach(element => {
            fadeInObserver.observe(element);
        });

        // Prevent layout shift
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>