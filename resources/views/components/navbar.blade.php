<!-- 📱 MOBILE TOP BAR (Hanya di HP) -->
<div class="md:hidden bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-white/50 px-4 h-16 flex items-center justify-between shadow-sm">
    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
        <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Logo" class="w-8 h-8 rounded-full object-cover border border-white shadow-sm">
        <span class="font-black text-lg tracking-tight text-[var(--tx-text-dark)] uppercase">Trinexa</span>
    </a>
    <div class="flex items-center gap-3">
        <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-400">
            @if(auth()->check() && \App\Models\SiteNotification::where('user_id', auth()->id())->whereNull('read_at')->count() > 0)
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            @endif
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </a>
        <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full overflow-hidden border border-white shadow-sm">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-tertiary)] text-white flex items-center justify-center font-bold text-[10px]">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
            @endif
        </a>
    </div>
</div>

<!-- 🌐 TOP NAVBAR (Hanya di Laptop/Desktop) -->
<header class="hidden md:block bg-white/70 backdrop-blur-md sticky top-0 z-50 border-b border-white/50 shadow-[0_4px_30px_rgb(0,0,0,0.03)]">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
        
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa Logo" class="w-10 h-10 object-cover rounded-full shadow-md border-2 border-white">
                <h1 class="text-2xl font-extrabold text-[var(--tx-text-dark)] tracking-wider">TRINEXA</h1>
            </a>
        </div>

        <!-- Menu Desktop -->
        <nav class="hidden md:flex gap-1 lg:gap-2 items-center">
            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'bg-[var(--tx-primary)] text-white px-4 py-2 rounded-full font-bold shadow-md shadow-[var(--tx-primary)]/30' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] font-semibold transition-colors px-3 py-2' }}">Dashboard</a>
            <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'bg-[var(--tx-primary)] text-white px-4 py-2 rounded-full font-bold shadow-md shadow-[var(--tx-primary)]/30' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] font-semibold transition-colors px-3 py-2' }}">Belanja</a>
            <a href="{{ route('user.wallet.show') }}" class="{{ request()->routeIs('user.wallet.*') ? 'bg-[var(--tx-primary)] text-white px-4 py-2 rounded-full font-bold shadow-md shadow-[var(--tx-primary)]/30' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] font-semibold transition-colors px-3 py-2' }}">Dompet</a>
            <a href="{{ route('dermatology.index') }}" class="{{ request()->routeIs('dermatology.*') ? 'bg-[var(--tx-primary)] text-white px-4 py-2 rounded-full font-bold shadow-md shadow-[var(--tx-primary)]/30' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] font-semibold transition-colors px-3 py-2' }}">Dermatology</a>
            <a href="{{ route('user.loyalty.index') }}" class="{{ request()->routeIs('user.loyalty.*') ? 'bg-[var(--tx-primary)] text-white px-4 py-2 rounded-full font-bold shadow-md shadow-[var(--tx-primary)]/30' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] font-semibold transition-colors px-3 py-2' }}">Loyalty</a>
            <a href="{{ route('konsultasi.index') }}" class="{{ request()->routeIs('konsultasi.*') ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white px-4 py-2 rounded-full font-bold shadow-md shadow-[var(--tx-secondary)]/30 flex items-center gap-1.5' : 'text-[var(--tx-secondary)] hover:text-[var(--tx-primary)] font-black transition-colors px-3 py-2 flex items-center gap-1.5' }}">🤖 Truevera AI</a>
        </nav>

        <!-- Kanan: Ikon & Profil -->
        <div class="flex items-center gap-4">
            
            <!-- Keranjang -->
            <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-400 hover:text-[var(--tx-primary)] transition-colors rounded-full hover:bg-gray-50">
                @php $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0; @endphp
                @if($cartCount > 0)
                <span class="absolute top-0 right-0 flex h-4 w-4">
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-[var(--tx-secondary)] text-[8px] font-bold text-white items-center justify-center" id="cart-badge-nav">{{ $cartCount }}</span>
                </span>
                @else
                <span class="absolute top-0 right-0 flex h-4 w-4 hidden" id="cart-badge-nav-container">
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-[var(--tx-secondary)] text-[8px] font-bold text-white items-center justify-center" id="cart-badge-nav">0</span>
                </span>
                @endif
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </a>

            <!-- Notifikasi -->
            <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-400 hover:text-[var(--tx-primary)] transition-colors rounded-full hover:bg-gray-50">
                @php 
                    $unreadCount = auth()->check() ? \App\Models\SiteNotification::where('user_id', auth()->id())->whereNull('read_at')->count() : 0;
                @endphp
                @if($unreadCount > 0)
                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
                @endif
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </a>
            
            <!-- Profil -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="hidden md:flex items-center gap-2 bg-white/50 px-4 py-2 rounded-full border border-white shadow-sm hover:border-[var(--tx-primary)] transition-all group">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center shadow-inner border border-white bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-tertiary)]">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-bold text-xs">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                        @endif
                    </div>
                    <span class="text-sm font-semibold text-[var(--tx-text-dark)] group-hover:text-[var(--tx-primary)]">{{ Auth::user()->name ?? 'User' }}</span>
                </button>

                <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-2 border border-gray-100 z-50 overflow-hidden">
                    {{-- User Info Header --}}
                    <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                        <div class="font-bold text-[#0F2942] text-sm truncate">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                    @if(auth()->user() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#FDF8F0] hover:text-[var(--tx-primary)] transition-colors">
                        <span>👑</span> Admin Panel
                    </a>
                    <hr class="border-gray-100 my-1">
                    @endif
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-[var(--tx-primary)] bg-gradient-to-r from-[var(--tx-primary-light)] to-transparent hover:bg-[var(--tx-primary-light)] transition-colors">
                        <span>🌐</span> Beranda Utama
                    </a>
                    <hr class="border-gray-100 my-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#FDF8F0] hover:text-[var(--tx-primary)] transition-colors">
                        <span>👤</span> Profil Saya
                    </a>
                    <a href="{{ route('transaction.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#FDF8F0] hover:text-[var(--tx-primary)] transition-colors">
                        <span>📦</span> Riwayat Pesanan
                    </a>
                    <a href="{{ route('user.wallet.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#FDF8F0] hover:text-[var(--tx-primary)] transition-colors">
                        <span>💳</span> Dompet Harvestly
                    </a>
                    <hr class="border-gray-100 my-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                            <span>🚪</span> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- 📱 BOTTOM NAVIGATION (Hanya di HP) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-white/40 flex justify-around items-center py-2 pb-6 z-40 shadow-[0_-10px_40px_rgba(0,0,0,0.08)] rounded-t-[32px] will-change-transform">
    <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('user.dashboard') ? 'text-[var(--tx-primary)]' : 'text-gray-400' }} transition-all duration-300">
        <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('user.dashboard') ? 'bg-[var(--tx-primary)] text-white shadow-lg shadow-[var(--tx-primary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        </div>
        <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Beranda') }}</span>
    </a>
    
    <a href="{{ route('shop.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('shop.*') ? 'text-[var(--tx-secondary)]' : 'text-gray-400' }} transition-all duration-300">
        <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('shop.*') ? 'bg-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-secondary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            @php $cartCountMobile = auth()->check() ? auth()->user()->cartItems()->count() : 0; @endphp
            @if($cartCountMobile > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 bg-red-500 border-2 border-white rounded-full text-[8px] font-black text-white items-center justify-center">{{ $cartCountMobile }}</span>
            @endif
        </div>
        <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Belanja') }}</span>
    </a>

    <a href="{{ route('user.wallet.show') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('user.wallet.*') ? 'text-[var(--tx-primary)]' : 'text-gray-400' }} transition-all duration-300">
        <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('user.wallet.*') ? 'bg-[var(--tx-primary)] text-white shadow-lg shadow-[var(--tx-primary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
        </div>
        <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Dompet') }}</span>
    </a>

    <a href="{{ route('dermatology.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('dermatology.*') ? 'text-[var(--tx-quaternary)]' : 'text-gray-400' }} transition-all duration-300">
        <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('dermatology.*') ? 'bg-[var(--tx-quaternary)] text-white shadow-lg shadow-[var(--tx-quaternary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Edukasi') }}</span>
    </a>

    <a href="{{ route('konsultasi.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('konsultasi.*') ? 'text-[var(--tx-tertiary)]' : 'text-gray-400' }} transition-all duration-300">
        <div class="relative p-2.5 rounded-2xl {{ request()->routeIs('konsultasi.*') ? 'bg-[var(--tx-tertiary)] text-white shadow-lg shadow-[var(--tx-tertiary)]/30 scale-110' : 'bg-transparent text-gray-400' }}">
            <span class="text-xl leading-none">🤖</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-tighter">AI Chat</span>
    </a>
</nav>
