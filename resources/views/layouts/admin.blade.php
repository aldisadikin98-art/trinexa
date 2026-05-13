<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Panel' }} | Trinexa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="bg-gradient-to-br from-[#FAFAFA] via-[#FDF8F0] to-[#FEF5F7] text-[var(--tx-text-dark)] antialiased flex h-screen overflow-hidden selection:bg-[var(--tx-secondary)]/20 relative">

    <!-- Dekorasi Ambient Halus -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-[var(--tx-secondary-light)] opacity-40 blur-[120px]"></div>
        <div class="absolute top-[30%] -right-[20%] w-[60vw] h-[60vw] rounded-full bg-[var(--tx-primary-light)] opacity-30 blur-[140px]"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[70vw] h-[70vw] rounded-full bg-[var(--tx-tertiary-light)] opacity-30 blur-[150px]"></div>
    </div>

    {{-- Sidebar (Premium Glassmorphism) --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:relative w-72 h-[calc(100vh-2rem)] m-4 rounded-[2rem] bg-white/70 backdrop-blur-2xl border border-white shadow-[0_10px_40px_rgba(0,0,0,0.04)] text-[var(--tx-text-dark)] flex flex-col shrink-0 z-50 transition-transform duration-300 overflow-hidden">
        <div class="h-24 flex items-center justify-between px-8 border-b border-white/80 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Logo" class="w-10 h-10 object-cover rounded-[12px] shadow-sm">
                <h1 class="text-xl font-black tracking-wider text-[var(--tx-text-dark)] flex flex-col">
                    TRINEXA
                    <span class="bg-[var(--tx-primary)] text-white text-[9px] px-2 py-0.5 rounded-full shadow-sm uppercase tracking-widest inline-block w-max mt-0.5">Admin</span>
                </h1>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-gray-400 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
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
            <a href="{{ route('admin.financial.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.financial.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">💰</span> Keuangan
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/20 font-black' : 'text-gray-500 hover:text-[var(--tx-primary)] hover:bg-white/80 font-bold' }}">
                <span class="text-xl">🔔</span> Notifikasi
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
            <a href="{{ route('user.dashboard') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-white border border-[var(--tx-primary)]/20 text-[var(--tx-primary)] hover:bg-[var(--tx-primary-light)] transition-all font-black text-xs uppercase tracking-widest shadow-sm">
                🌐 Ke Web User
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
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10 p-2 md:p-4">
        {{-- Header (Premium Floating) --}}
        <header class="h-20 bg-white/70 backdrop-blur-2xl border border-white rounded-3xl flex items-center justify-between px-8 shrink-0 z-10 shadow-[0_4px_30px_rgb(0,0,0,0.03)] mb-4">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="md:hidden p-2 bg-white/50 rounded-xl border border-white shadow-sm text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-xl md:text-2xl font-black text-[var(--tx-text-dark)] drop-shadow-sm">{{ $title ?? 'Dashboard' }}</h2>
            </div>
            <div class="flex items-center gap-4">
                {{-- Logout Button (Mobile Only) --}}
                <form action="{{ route('logout') }}" method="POST" class="md:hidden">
                    @csrf
                    <button class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100 shadow-sm">
                        <span class="text-xl">🚪</span>
                    </button>
                </form>
                
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-sm font-black text-[var(--tx-text-dark)]">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Administrator</span>
                </div>
                <div class="w-11 h-11 rounded-[14px] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center font-black text-lg shadow-lg shadow-[var(--tx-primary)]/30 border border-white/50">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto no-scrollbar pb-24 md:pb-8 rounded-3xl">
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

    {{-- 📱 ADMIN BOTTOM NAVIGATION (Hanya di HP) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-2xl border-t border-white/40 flex justify-around items-center py-2 pb-6 z-[60] shadow-[0_-10px_40px_rgba(0,0,0,0.08)] rounded-t-[32px]">
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('admin.dashboard') ? 'text-[var(--tx-primary)]' : 'text-gray-400' }} transition-all duration-300">
            <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'bg-[var(--tx-primary)] text-white shadow-lg shadow-[var(--tx-primary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
                <span class="text-xl leading-none">📊</span>
            </div>
            <span class="text-[9px] font-black uppercase tracking-tighter">Dash</span>
        </a>
        
        <a href="{{ route('admin.produk.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('admin.produk.*') ? 'text-[var(--tx-secondary)]' : 'text-gray-400' }} transition-all duration-300">
            <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('admin.produk.*') ? 'bg-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-secondary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
                <span class="text-xl leading-none">🧴</span>
            </div>
            <span class="text-[9px] font-black uppercase tracking-tighter">Produk</span>
        </a>

        <a href="{{ route('admin.pesanan.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('admin.pesanan.*') ? 'text-[var(--tx-primary)]' : 'text-gray-400' }} transition-all duration-300">
            <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('admin.pesanan.*') ? 'bg-[var(--tx-primary)] text-white shadow-lg shadow-[var(--tx-primary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
                <span class="text-xl leading-none">📦</span>
            </div>
            <span class="text-[9px] font-black uppercase tracking-tighter">Order</span>
        </a>

        <a href="{{ route('admin.financial.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('admin.financial.*') ? 'text-[var(--tx-quaternary)]' : 'text-gray-400' }} transition-all duration-300">
            <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('admin.financial.*') ? 'bg-[var(--tx-quaternary)] text-white shadow-lg shadow-[var(--tx-quaternary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
                <span class="text-xl leading-none">💰</span>
            </div>
            <span class="text-[9px] font-black uppercase tracking-tighter">Finance</span>
        </a>

        <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center gap-1 p-2 text-gray-400 transition-all duration-300">
            <div class="relative p-2.5 rounded-2xl bg-transparent text-gray-400">
                <span class="text-xl leading-none">🌐</span>
            </div>
            <span class="text-[9px] font-black uppercase tracking-tighter">Web</span>
        </a>
    </nav>

    @stack('scripts')
</body>
</html>
