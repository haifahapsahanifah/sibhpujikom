<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Barang Habis Pakai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            font-family: 'Inter', sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .countdown-timer {
            font-family: monospace;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-card w-full max-w-md p-8">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl mb-4">
                <i class="fas fa-boxes text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">SIPBHP</h1>
            <p class="text-gray-600">Sistem Informasi Pengajuan Barang Habis Pakai</p>
        </div>
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <div class="mb-6">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-blue-600"></i>Username
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username') }}"
                       required 
                       autofocus
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                       placeholder="Masukkan username">
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                       placeholder="Masukkan password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @if ($errors->has('login'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    <p class="text-red-600 text-sm inline">{{ $errors->first('login') }}</p>
                    @if(session('lockout_remaining') || isset($lockout_remaining))
                        <div class="mt-2 text-center">
                            <div class="countdown-timer text-red-600" id="countdownTimer"></div>
                        </div>
                    @endif
                </div>
            @endif
            
            @if(session('remaining_attempts'))
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    <p class="text-yellow-700 text-sm">
                        Peringatan: {{ session('remaining_attempts') }} percobaan tersisa sebelum akun terkunci.
                    </p>
                </div>
            @endif
            
            <button type="submit" class="btn-primary w-full text-white py-3 rounded-lg font-medium mb-4" id="submitBtn">
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </button>
            
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Halaman Utama
                </a>
            </div>
        </form>
        
        <!-- Info -->
        <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="flex items-center justify-center text-sm text-gray-600">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                <span>Gunakan username dan password yang telah diberikan</span>
            </div>
        </div>
    </div>

    <script>
        // Timer countdown untuk lockout
        let lockoutRemaining = {{ session('lockout_remaining') ?? (isset($lockout_remaining) ? $lockout_remaining : 0) }};
        
        function startCountdown(seconds) {
            const submitBtn = document.getElementById('submitBtn');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const countdownElement = document.getElementById('countdownTimer');
            
            // Disable form inputs
            submitBtn.disabled = true;
            usernameInput.disabled = true;
            passwordInput.disabled = true;
            
            let remaining = seconds;
            
            const interval = setInterval(() => {
                if (remaining <= 0) {
                    clearInterval(interval);
                    submitBtn.disabled = false;
                    usernameInput.disabled = false;
                    passwordInput.disabled = false;
                    if (countdownElement) {
                        countdownElement.innerHTML = '';
                    }
                } else {
                    const minutes = Math.floor(remaining / 60);
                    const secs = remaining % 60;
                    const timeString = `${minutes}:${secs.toString().padStart(2, '0')}`;
                    if (countdownElement) {
                        countdownElement.innerHTML = `⏱️ Waktu tunggu: ${timeString} detik`;
                    }
                    remaining--;
                }
            }, 1000);
        }
        
        // Start timer if lockout is active
        if (lockoutRemaining > 0) {
            startCountdown(lockoutRemaining);
        }
        
        // Handle remaining attempts warning
        const remainingAttempts = {{ session('remaining_attempts') ?? 0 }};
        if (remainingAttempts === 1) {
            // Add visual warning for last attempt
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.style.background = "linear-gradient(135deg, #f59e0b 0%, #d97706 100%)";
        }
    </script>
</body>
</html>