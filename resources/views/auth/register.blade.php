<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Trinexa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-mesh text-[var(--tx-text-dark)] antialiased min-h-screen flex items-center justify-center p-4 sm:p-8">

    <div class="w-full max-w-5xl flex flex-col md:flex-row rounded-[2rem] overflow-hidden glass-card shadow-[0_20px_50px_rgba(0,0,0,0.05)] relative z-10 border-2 border-white/60">
        
        <!-- Left Column: Branding / Illustration -->
        <div class="hidden md:flex flex-col justify-between w-1/2 p-12 relative overflow-hidden bg-white/30 backdrop-blur-sm">
            <!-- Decorative Gradients inside card -->
            <div class="absolute top-[-20%] left-[-20%] w-[350px] h-[350px] bg-[var(--tx-primary)] rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[300px] h-[300px] bg-[var(--tx-secondary)] rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
            
            <div class="relative z-10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 mb-16 inline-flex group">
                    <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa" class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-white group-hover:scale-105 transition-transform">
                    <h1 class="text-3xl font-black text-[var(--tx-text-dark)] tracking-wider">TRINEXA</h1>
                </a>

                <h2 class="text-5xl font-black leading-tight mb-6">
                    Mulai Perjalanan<br>
                    <span class="text-gradient">Luar Biasamu</span>
                </h2>
                <p class="text-lg font-medium text-[var(--tx-text-muted)] max-w-sm mb-12">
                    Buat akun sekarang dan nikmati ekosistem terpadu untuk kecantikan, keuangan, dan gaya hidup.
                </p>

                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center overflow-hidden shadow-sm border border-white shrink-0">
                            <img src="{{ asset('images/logo harves.jpeg') }}" alt="Harves" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Harvestly Wallet</h4>
                            <p class="text-[11px] text-[var(--tx-text-muted)] font-medium">Cek saldo & bayar instan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center overflow-hidden shadow-sm border border-white shrink-0">
                            <img src="{{ asset('images/logo natur.jpeg') }}" alt="Natur" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Naturea Shop</h4>
                            <p class="text-[11px] text-[var(--tx-text-muted)] font-medium">Belanja skincare organik favoritmu</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center overflow-hidden shadow-sm border border-white shrink-0">
                            <img src="{{ asset('images/logo karebla.jpeg') }}" alt="Karebla" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Karebla Points</h4>
                            <p class="text-[11px] text-[var(--tx-text-muted)] font-medium">Tukar botol kosong menjadi diskon</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 mt-12 text-xs text-[var(--tx-text-muted)] font-bold">
                © 2026 Trinexa. All rights reserved.
            </div>
        </div>

        <!-- Right Column: Register Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 md:p-16 bg-white/70 backdrop-blur-xl flex flex-col justify-center border-l border-white/50 relative">
            
            <!-- Mobile Logo -->
            <div class="md:hidden flex justify-center mb-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa" class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-white">
                    <h1 class="text-3xl font-black text-[var(--tx-text-dark)] tracking-wider">TRINEXA</h1>
                </a>
            </div>

            <div class="text-center md:text-left mb-8">
                <h2 class="text-3xl sm:text-4xl font-black text-[var(--tx-text-dark)] mb-2">Buat Akun 🚀</h2>
                <p class="text-[var(--tx-text-muted)] font-medium text-sm">Lengkapi data di bawah ini untuk bergabung dengan Trinexa.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block font-extrabold text-xs text-[var(--tx-text-dark)] mb-2 ml-1 uppercase tracking-wider">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--tx-primary)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                            class="w-full pl-12 pr-4 py-3 sm:py-4 bg-white/60 border-2 border-white rounded-2xl focus:border-[var(--tx-primary)] focus:bg-white focus:ring-0 transition-all duration-300 font-medium text-[var(--tx-text-dark)] placeholder-gray-400 shadow-sm" 
                            placeholder="Masukkan nama lengkap">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-xs font-bold ml-1" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-extrabold text-xs text-[var(--tx-text-dark)] mb-2 ml-1 uppercase tracking-wider">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--tx-primary)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                            class="w-full pl-12 pr-4 py-3 sm:py-4 bg-white/60 border-2 border-white rounded-2xl focus:border-[var(--tx-primary)] focus:bg-white focus:ring-0 transition-all duration-300 font-medium text-[var(--tx-text-dark)] placeholder-gray-400 shadow-sm" 
                            placeholder="nama@email.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-bold ml-1" />
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-1/2">
                        <label for="password" class="block font-extrabold text-xs text-[var(--tx-text-dark)] mb-2 ml-1 uppercase tracking-wider">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--tx-primary)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password" 
                                class="w-full pl-12 pr-12 py-3 sm:py-4 bg-white/60 border-2 border-white rounded-2xl focus:border-[var(--tx-primary)] focus:bg-white focus:ring-0 transition-all duration-300 font-medium text-[var(--tx-text-dark)] placeholder-gray-400 shadow-sm" 
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[var(--tx-primary)] transition-colors">
                                <svg class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs font-bold ml-1" />
                    </div>

                    <div class="w-full sm:w-1/2">
                        <label for="password_confirmation" class="block font-extrabold text-xs text-[var(--tx-text-dark)] mb-2 ml-1 uppercase tracking-wider">Konfirmasi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--tx-primary)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                class="w-full pl-12 pr-12 py-3 sm:py-4 bg-white/60 border-2 border-white rounded-2xl focus:border-[var(--tx-primary)] focus:bg-white focus:ring-0 transition-all duration-300 font-medium text-[var(--tx-text-dark)] placeholder-gray-400 shadow-sm" 
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[var(--tx-primary)] transition-colors">
                                <svg class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs font-bold ml-1" />
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient w-full mt-4 py-4 text-lg rounded-2xl">
                    Daftar Sekarang
                </button>

                <!-- Login Link -->
                <p class="text-center text-sm text-[var(--tx-text-muted)] mt-4 font-medium">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-extrabold text-[var(--tx-primary)] hover:text-[var(--tx-secondary)] transition-colors">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>

    <script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
    </script>
</body>
</html>
