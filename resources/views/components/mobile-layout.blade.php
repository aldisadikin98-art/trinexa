<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ $title ?? 'Trinexa' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900 selection:bg-soft-pink selection:text-white">
    <div class="max-w-[414px] mx-auto min-h-screen bg-white shadow-2xl relative pb-24 overflow-hidden flex flex-col">
        
        <!-- HEADER -->
        @if(isset($header))
        <div class="px-5 pt-8 pb-4 flex justify-between items-center bg-white sticky top-0 z-10 shadow-sm">
            {{ $header }}
        </div>
        @endif

        <!-- CONTENT -->
        <div class="px-5 space-y-6 mt-4 overflow-y-auto flex-1">
            {{ $slot }}
        </div>

        <!-- Floating AI Chat Button -->
        <button class="absolute bottom-20 right-4 bg-soft-pink text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-pink-400 transition-transform hover:scale-105 z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </button>

        <!-- Bottom Navigation Bar -->
        <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-100 flex justify-around items-center h-16 pb-safe z-20">
            <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('user.dashboard') ? 'text-soft-pink' : 'text-gray-400 hover:text-soft-pink' }} transition-colors">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span class="text-[10px] font-medium">Beranda</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-soft-pink transition-colors">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="text-[10px] font-medium">Belanja</span>
            </a>
            <a href="{{ route('user.wallet.show') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('user.wallet.*') ? 'text-soft-pink' : 'text-gray-400 hover:text-soft-pink' }} transition-colors">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="text-[10px] font-medium">Dompet</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full h-full">
                @csrf
                <button type="submit" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-soft-pink transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="text-[10px] font-medium">Keluar</span>
                </button>
            </form>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
