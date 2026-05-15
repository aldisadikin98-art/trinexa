<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Trinexa') }} - Autentikasi</title>
        <!-- Font Premium -->
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
    </head>
    
    <!-- 
      PERBAIKAN: 
      - Hapus 'overflow-hidden' agar bisa di-scroll.
      - Ganti 'min-h-screen' menjadi 'min-h-[100dvh]' agar lebih bersahabat dengan browser HP.
      - Tambahkan 'py-12' agar ada jarak aman di atas dan bawah saat di-scroll.
    -->
    <body class="font-sans text-gray-900 antialiased bg-gray-50 relative min-h-[100dvh] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-[#F8C8DC] selection:text-gray-900 overflow-x-hidden">
        
        <!-- Ornamen Background Gradasi Mewah (Dibuat fixed agar tidak ikut terscroll) -->
        <div class="fixed top-0 left-0 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-gradient-to-br from-[#F5E6DA] to-transparent rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 -z-10"></div>
        <div class="fixed bottom-0 right-0 w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] bg-gradient-to-tl from-[#F8C8DC]/40 to-transparent rounded-full blur-3xl translate-x-1/3 translate-y-1/3 -z-10"></div>

        <!-- Wadah Konten Utama -->
        <div class="w-full max-w-md flex flex-col items-center relative z-10">
            
            <!-- Logo -->
            <div class="mb-8 mt-4 sm:mt-0">
                <a href="/" class="flex items-center gap-2 sm:gap-3 group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#F5E6DA] rounded-xl sm:rounded-2xl flex items-center justify-center text-[#D4AF37] font-bold text-2xl sm:text-3xl shadow-inner group-hover:scale-110 transition-transform">T</div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] to-yellow-600 tracking-wider">TRINEXA</h1>
                </a>
            </div>

            <!-- Kartu Kaca (Glassmorphism Form) -->
            <!-- Tambahkan padding proporsional untuk HP (p-6) dan Desktop (sm:p-10) -->
            <div class="w-full bg-white/80 backdrop-blur-xl shadow-2xl border border-white rounded-3xl sm:rounded-[2rem] p-6 sm:p-10">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>
