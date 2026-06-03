<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Trinexa</title>
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
                    Akses Dunia<br>
                    <span class="text-gradient">Super App</span>
                </h2>
                <p class="text-lg font-medium text-[var(--tx-text-muted)] max-w-sm mb-12">
                    Masuk untuk mengelola keuangan, berbelanja skincare, dan menikmati berbagai reward eksklusif.
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

        <!-- Right Column: Login Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 md:p-16 bg-white/70 backdrop-blur-xl flex flex-col justify-center border-l border-white/50 relative">
            
            <!-- Mobile Logo (Visible only on small screens) -->
            <div class="md:hidden flex justify-center mb-10">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa" class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-white">
                    <h1 class="text-3xl font-black text-[var(--tx-text-dark)] tracking-wider">TRINEXA</h1>
                </a>
            </div>

            <div class="text-center md:text-left mb-10">
                <h2 class="text-3xl sm:text-4xl font-black text-[var(--tx-text-dark)] mb-3">Selamat Datang 👋</h2>
                <p class="text-[var(--tx-text-muted)] font-medium text-sm">Silakan masukkan detail akun Anda untuk melanjutkan.</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 font-bold text-sm text-[var(--tx-primary)] bg-[var(--tx-primary-light)] p-4 rounded-xl border border-[var(--tx-primary)]/20">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-extrabold text-xs text-[var(--tx-text-dark)] mb-2 ml-1 uppercase tracking-wider">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--tx-primary)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            class="w-full pl-12 pr-4 py-4 bg-white/60 border-2 border-white rounded-2xl focus:border-[var(--tx-primary)] focus:bg-white focus:ring-0 transition-all duration-300 font-medium text-[var(--tx-text-dark)] placeholder-gray-400 shadow-sm" 
                            placeholder="nama@email.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-bold ml-1" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-extrabold text-xs text-[var(--tx-text-dark)] mb-2 ml-1 uppercase tracking-wider">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--tx-primary)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                            class="w-full pl-12 pr-12 py-4 bg-white/60 border-2 border-white rounded-2xl focus:border-[var(--tx-primary)] focus:bg-white focus:ring-0 transition-all duration-300 font-medium text-[var(--tx-text-dark)] placeholder-gray-400 shadow-sm" 
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[var(--tx-primary)] transition-colors">
                            <svg class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-bold ml-1" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-2 px-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 rounded-md border-gray-300 text-[var(--tx-primary)] shadow-sm focus:ring-[var(--tx-primary)]/50 transition-colors" name="remember">
                        <span class="ms-2 text-sm text-[var(--tx-text-muted)] group-hover:text-[var(--tx-text-dark)] font-bold transition-colors">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-[var(--tx-primary)] hover:text-[var(--tx-secondary)] transition-colors" href="{{ route('password.request') }}">
                            Lupa sandi?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient w-full mt-6 py-4 text-lg rounded-2xl">
                    Masuk
                </button>

                <!-- OR Separator -->
                <div class="flex items-center gap-4 my-2">
                    <div class="flex-1 h-[2px] bg-white"></div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Atau</span>
                    <div class="flex-1 h-[2px] bg-white"></div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('auth.google') }}" class="w-full py-4 bg-white border-2 border-white rounded-2xl flex items-center justify-center gap-3 font-extrabold text-[var(--tx-text-dark)] hover:bg-gray-50 transition-all duration-300 shadow-sm">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5">
                    Masuk dengan Google
                </a>

                <!-- Register Link -->
                <p class="text-center text-sm text-[var(--tx-text-muted)] mt-6 font-medium">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-extrabold text-[var(--tx-primary)] hover:text-[var(--tx-secondary)] transition-colors">Daftar sekarang</a>
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
