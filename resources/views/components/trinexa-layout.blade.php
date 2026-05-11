<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Trinexa - Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Premium untuk kesan "Wah" -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-tx-bg min-h-screen text-gray-800 antialiased selection:bg-tx-secondary-light selection:text-tx-secondary">

    <!-- 🌐 TOP NAVBAR (Hanya di Laptop/Desktop) -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa Logo" class="w-10 h-10 object-cover rounded-2xl shadow-md border border-white/50">
                    <h1 class="text-2xl font-extrabold text-tx-primary tracking-wider">TRINEXA</h1>
                </a>
            </div>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex gap-8 items-center">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-tx-primary font-extrabold border-b-[3px] border-tx-primary pb-1' : 'text-gray-400 hover:text-tx-primary font-bold transition-colors' }}">Beranda</a>
                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'text-tx-primary font-extrabold border-b-[3px] border-tx-primary pb-1' : 'text-gray-400 hover:text-tx-primary font-bold transition-colors' }}">Dashboard</a>
                <a href="{{ route('user.shop.index') }}" class="{{ request()->routeIs('user.shop.*') ? 'text-tx-primary font-extrabold border-b-[3px] border-tx-primary pb-1' : 'text-gray-400 hover:text-tx-primary font-bold transition-colors' }}">Belanja</a>
                <a href="{{ route('user.wallet.show') }}" class="{{ request()->routeIs('user.wallet.*') ? 'text-tx-primary font-extrabold border-b-[3px] border-tx-primary pb-1' : 'text-gray-400 hover:text-tx-primary font-bold transition-colors' }}">Dompet</a>
                <a href="{{ route('dermatology.index') }}" class="{{ request()->routeIs('dermatology.*') ? 'text-tx-primary font-extrabold border-b-[3px] border-tx-primary pb-1' : 'text-gray-400 hover:text-tx-primary font-bold transition-colors' }}">Dermatology</a>
                <a href="{{ route('user.loyalty.index') }}" class="{{ request()->routeIs('user.loyalty.*') ? 'text-tx-primary font-extrabold border-b-[3px] border-tx-primary pb-1' : 'text-gray-400 hover:text-tx-primary font-bold transition-colors' }}">Loyalty</a>
            </nav>

            <!-- Profil Desktop -->
            <div class="flex items-center gap-4">
                <button class="relative p-2 text-gray-400 hover:text-tx-primary transition-colors rounded-full hover:bg-tx-primary-light">
                    <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tx-primary opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-tx-primary"></span>
                    </span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                
                <!-- Fitur Logout Bawaan Laravel -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hidden md:flex items-center gap-2 bg-white px-4 py-2 rounded-[16px] border-2 border-tx-primary-light shadow-sm hover:border-tx-primary transition-all group">
                        <div class="w-7 h-7 rounded-[10px] bg-tx-primary text-white flex items-center justify-center font-bold text-xs">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-tx-primary">{{ Auth::user()->name ?? 'User' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{ $slot }}

    <!-- 🤖 TOMBOL AI MENGAMBANG (Floating Button) -->
    <button class="fixed bottom-24 md:bottom-10 right-5 md:right-10 bg-tx-primary text-white px-5 py-4 rounded-[20px] shadow-lg shadow-blue-200/50 border-4 border-white flex items-center gap-2 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 z-50">
        <span class="text-2xl drop-shadow-md">✨</span>
        <span class="text-sm font-extrabold tracking-wide pr-1">Tanya AI</span>
    </button>

    <!-- 📱 BOTTOM NAVIGATION (Hanya di HP) -->
    <nav class="md:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-md border-t border-gray-100 flex justify-around items-center py-2 pb-5 z-40 shadow-[0_-10px_40px_rgba(0,0,0,0.04)]">
        <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('user.dashboard') ? 'text-[#D4AF37]' : 'text-gray-400 hover:text-[#D4AF37]' }} transition-colors group">
            <div class="{{ request()->routeIs('user.dashboard') ? 'bg-yellow-50' : '' }} p-2 rounded-xl group-hover:bg-yellow-50 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Beranda</span>
        </a>
        <a href="{{ route('user.shop.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('user.shop.*') ? 'text-tx-primary' : 'text-gray-400 hover:text-tx-primary' }} transition-colors group">
            <div class="{{ request()->routeIs('user.shop.*') ? 'bg-tx-primary-light text-tx-primary' : '' }} p-2 rounded-xl group-hover:bg-tx-primary-light group-hover:text-tx-primary group-hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('user.shop.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Belanja</span>
        </a>
        <a href="{{ route('user.wallet.show') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('user.wallet.*') ? 'text-tx-primary' : 'text-gray-400 hover:text-tx-primary' }} transition-colors group">
            <div class="{{ request()->routeIs('user.wallet.*') ? 'bg-tx-primary-light text-tx-primary' : '' }} p-2 rounded-xl group-hover:bg-tx-primary-light group-hover:text-tx-primary group-hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('user.wallet.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Dompet</span>
        </a>
        <a href="{{ route('dermatology.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('dermatology.*') ? 'text-tx-primary' : 'text-gray-400 hover:text-tx-primary' }} transition-colors group">
            <div class="{{ request()->routeIs('dermatology.*') ? 'bg-tx-primary-light text-tx-primary' : '' }} p-2 rounded-xl group-hover:bg-tx-primary-light group-hover:text-tx-primary group-hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Edukasi</span>
        </a>
        <a href="{{ route('user.loyalty.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('user.loyalty.*') ? 'text-tx-primary' : 'text-gray-400 hover:text-tx-primary' }} transition-colors group">
            <div class="{{ request()->routeIs('user.loyalty.*') ? 'bg-tx-primary-light text-tx-primary' : '' }} p-2 rounded-xl group-hover:bg-tx-primary-light group-hover:text-tx-primary group-hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('user.loyalty.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Loyalty</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 p-2 text-gray-400 hover:text-tx-primary transition-colors group">
            <div class="p-2 rounded-xl group-hover:bg-tx-primary-light group-hover:text-tx-primary group-hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Akun</span>
        </a>
    </nav>
    @stack('scripts')
</body>
</html>
