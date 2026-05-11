@props(['title' => 'Admin Panel'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | Trinexa Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mesh text-[var(--tx-text-dark)] antialiased flex h-screen overflow-hidden selection:bg-[var(--tx-secondary)]/20 relative">

    <!-- Background relies on bg-mesh -->
    {{-- Sidebar (Premium Glassmorphism) --}}
    <aside class="w-72 m-4 rounded-[2rem] bg-white/70 backdrop-blur-2xl border border-white shadow-[0_10px_40px_rgba(0,0,0,0.04)] text-[var(--tx-text-dark)] flex flex-col hidden md:flex shrink-0 z-20 overflow-hidden relative">
        <div class="h-24 flex items-center px-8 border-b border-white/80 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Logo" class="w-10 h-10 object-cover rounded-[12px] shadow-sm">
                <h1 class="text-xl font-black tracking-wider text-[var(--tx-text-dark)] flex flex-col">
                    TRINEXA
                    <span class="bg-[var(--tx-primary)] text-white text-[9px] px-2 py-0.5 rounded-full shadow-sm uppercase tracking-widest inline-block w-max mt-0.5">Admin</span>
                </h1>
            </a>
        </div>
        
        <nav class="flex-1 px-5 py-6 space-y-2.5 overflow-y-auto no-scrollbar">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.produk.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">🧴</span> Produk
            </a>
            <a href="{{ route('admin.pesanan.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.pesanan.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">📦</span> Pesanan
            </a>
            <a href="{{ route('admin.ulasan.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.ulasan.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">⭐</span> Ulasan
            </a>
            <a href="{{ route('admin.voucher.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.voucher.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">🎟️</span> Voucher Shop
            </a>

            <!-- Menu Karebla -->
            <div class="pt-4 mt-2 border-t border-gray-200/50">
                <p class="px-5 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-3">Rewards System</p>
                
                <a href="{{ route('admin.karebla.produk.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.karebla.produk.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                    <span class="text-xl">💎</span> Karebla Produk
                </a>

                <a href="{{ route('admin.karebla.penukaran.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.karebla.penukaran.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                    <span class="text-xl">🔄</span> Penukaran Reward
                </a>
            </div>
        </nav>
        
        <!-- Footer Sidebar -->
        <div class="p-5 border-t border-white/80 space-y-3 bg-white/40 shrink-0">
            <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white hover:scale-105 transition-all font-black text-xs uppercase tracking-widest shadow-[0_4px_14px_0_rgba(244,114,182,0.39)]">
                🌐 Ke Beranda Utama
            </a>
            <a href="{{ route('user.dashboard') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-white border border-[var(--tx-primary)]/20 text-[var(--tx-primary)] hover:bg-[var(--tx-primary-light)] transition-all font-black text-xs uppercase tracking-widest shadow-sm">
                👤 Web User
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-red-50 text-red-500 hover:bg-red-100 transition-all font-black text-xs uppercase tracking-widest shadow-sm border border-red-100">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10 p-4 pl-0">
        {{-- Header (Premium Floating) --}}
        <header class="h-20 bg-white/70 backdrop-blur-2xl border border-white rounded-3xl flex items-center justify-between px-8 shrink-0 z-10 shadow-[0_4px_30px_rgb(0,0,0,0.03)] mb-4">
            <h2 class="text-2xl font-black text-[var(--tx-text-dark)] drop-shadow-sm">{{ $title ?? 'Dashboard' }}</h2>
            <div class="flex items-center gap-4">
                <div class="flex flex-col text-right">
                    <span class="text-sm font-black text-[var(--tx-text-dark)]">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Administrator</span>
                </div>
                <div class="w-11 h-11 rounded-[14px] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center font-black text-lg shadow-lg shadow-[var(--tx-primary)]/30 border border-white/50">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto no-scrollbar pb-8 rounded-3xl">
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50/90 backdrop-blur-md border border-green-200 text-green-700 px-6 py-4 rounded-3xl text-sm font-black shadow-sm">
                    <span class="text-xl">✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50/90 backdrop-blur-md border border-red-200 text-red-600 px-6 py-4 rounded-3xl text-sm font-black shadow-sm">
                    <span class="text-xl">❌</span> {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
