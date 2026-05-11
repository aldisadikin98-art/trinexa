<x-app-layout>
    <x-slot name="title">Naturea — Skincare Alami | Trinexa</x-slot>

    {{-- Hero Banner --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-card w-full p-8 md:p-12 relative overflow-hidden border border-white/50 bg-gradient-to-br from-[var(--tx-secondary)]/80 to-[#F8A4CF]/80 text-white mb-10 shadow-xl shadow-[var(--tx-secondary)]/20">
            <!-- Dekorasi Orb -->
            <div class="absolute right-0 top-0 w-80 h-80 bg-white/30 rounded-full blur-3xl translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 bg-white/30 backdrop-blur-md text-white px-4 py-1.5 rounded-full text-[10px] uppercase tracking-widest font-black mb-4 border border-white/50 shadow-sm">
                        ✨ NATUREA STORE
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black mb-3 leading-tight tracking-tight drop-shadow-sm">
                        Cantik Alami,<br><span class="text-white/90">Tanpa Ribet</span>
                    </h1>
                    <p class="text-white/90 text-sm max-w-md font-bold leading-relaxed">Temukan skincare berbahan organik terbaik. Kumpulkan koin tiap pembelian untuk ditukar reward seru!</p>
                </div>
                
                <div class="flex gap-4 shrink-0">
                    <div class="bg-white/20 backdrop-blur-md rounded-[24px] px-8 py-6 text-center border border-white/40 shadow-lg transform rotate-3 hover:rotate-0 transition-transform">
                        <div class="text-4xl font-black text-white drop-shadow-md">{{ $products->total() }}</div>
                        <div class="text-[10px] text-white/90 mt-1 font-black uppercase tracking-widest">Produk</div>
                    </div>
                    <a href="{{ route('cart.index') }}" class="bg-white/20 backdrop-blur-md rounded-[24px] px-8 py-6 text-center border border-white/40 shadow-lg transform -rotate-3 hover:rotate-0 transition-transform hover:bg-white/30 block group">
                        <div class="text-4xl font-black text-white drop-shadow-md group-hover:scale-110 transition-transform">{{ auth()->user()->cartItems()->count() }}</div>
                        <div class="text-[10px] text-white/90 mt-1 font-black uppercase tracking-widest">Keranjang</div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-8 flex items-center gap-3 bg-white/60 backdrop-blur-md border border-white/50 text-[var(--tx-quaternary)] font-black px-6 py-4 rounded-[16px] shadow-lg">
                <span class="text-xl">🌟</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-8 flex items-center gap-3 bg-white/60 backdrop-blur-md border border-white/50 text-red-500 font-black px-6 py-4 rounded-[16px] shadow-lg">
                <span class="text-xl">⚠️</span> {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ── SIDEBAR FILTER ────────────────────────────────────────── --}}
            <aside class="lg:w-72 shrink-0">
                <form id="filterForm" method="GET" action="{{ route('user.shop.index') }}">
                    <div class="glass-card p-6 md:p-8 sticky top-24">
                        <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[var(--tx-secondary-light)] text-[var(--tx-secondary)] flex items-center justify-center text-sm shadow-inner">🔍</span> 
                            Filter Produk
                        </h3>

                        {{-- Search --}}
                        <div class="mb-6">
                            <label class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 block">Cari Skincare</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Ketik nama produk..."
                                    class="w-full pl-10 pr-4 py-3 bg-white/50 border border-white/60 rounded-[16px] text-sm font-bold text-[var(--tx-text-dark)] focus:outline-none focus:border-[var(--tx-secondary)] focus:ring-4 focus:ring-[var(--tx-secondary-light)] transition-all placeholder:text-gray-400 backdrop-blur-sm">
                                <span class="absolute left-4 top-3.5 text-gray-400 text-sm">🔎</span>
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-6">
                            <label class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-3 block">Pilih Kategori</label>
                            <div class="space-y-3 bg-white/30 p-4 rounded-[16px] border border-white/50">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="w-5 h-5 accent-[var(--tx-secondary)] border border-white/60 bg-white/50 cursor-pointer">
                                    <span class="text-sm font-bold text-[var(--tx-text-dark)] group-hover:text-[var(--tx-secondary)] transition-colors">Semua Kategori</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="category" value="{{ $cat }}" {{ request('category') === $cat ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="w-5 h-5 accent-[var(--tx-secondary)] border border-white/60 bg-white/50 cursor-pointer">
                                        <span class="text-sm font-bold text-[var(--tx-text-dark)] group-hover:text-[var(--tx-secondary)] transition-colors">{{ $cat }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Sort --}}
                        <div class="mb-8">
                            <label class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 block">Urutkan</label>
                            <select name="sort" onchange="document.getElementById('filterForm').submit()" class="w-full py-3 px-4 bg-white/50 border border-white/60 rounded-[16px] text-sm font-bold text-[var(--tx-text-dark)] focus:outline-none focus:border-[var(--tx-secondary)] focus:ring-4 focus:ring-[var(--tx-secondary-light)] cursor-pointer appearance-none transition-all backdrop-blur-sm">
                                <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>✨ Terbaru</option>
                                <option value="terlaris" {{ request('sort') === 'terlaris' ? 'selected' : '' }}>🔥 Terlaris</option>
                                <option value="harga_terendah" {{ request('sort') === 'harga_terendah' ? 'selected' : '' }}>⬇️ Harga Terendah</option>
                                <option value="harga_tertinggi" {{ request('sort') === 'harga_tertinggi' ? 'selected' : '' }}>⬆️ Harga Tertinggi</option>
                            </select>
                        </div>

                        {{-- Active Filters --}}
                        @if(request()->hasAny(['search', 'category', 'sort']))
                            <div class="mb-8">
                                <label class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-3 block">Filter Aktif</label>
                                <div class="flex flex-wrap gap-2">
                                    @if(request('search'))
                                        <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="inline-flex items-center gap-1.5 bg-[var(--tx-secondary)] text-white text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-full hover:bg-pink-600 transition-colors shadow-sm">
                                            "{{ request('search') }}" <span class="bg-white/20 rounded-full w-4 h-4 flex items-center justify-center">✕</span>
                                        </a>
                                    @endif
                                    @if(request('category'))
                                        <a href="{{ request()->fullUrlWithoutQuery(['category']) }}" class="inline-flex items-center gap-1.5 bg-[var(--tx-secondary)] text-white text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-full hover:bg-pink-600 transition-colors shadow-sm">
                                            {{ request('category') }} <span class="bg-white/20 rounded-full w-4 h-4 flex items-center justify-center">✕</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('user.shop.index') }}" class="text-[10px] font-black text-[var(--tx-text-muted)] hover:text-red-500 underline self-center ml-1 uppercase tracking-widest transition-colors">Reset</a>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="w-full btn-gradient">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </aside>

            {{-- ── PRODUCT GRID ─────────────────────────────────────────── --}}
            <div class="flex-1">
                {{-- Result info --}}
                <div class="flex items-center justify-between mb-8 glass-card py-4 px-6 md:px-8 border border-white/50">
                    <p class="text-sm font-bold text-[var(--tx-text-muted)]">
                        Ketemu <span class="font-black text-[var(--tx-secondary)] text-lg px-1">{{ $products->total() }}</span> produk keren!
                    </p>
                    <a href="{{ route('cart.index') }}" class="btn-gradient py-2 px-5 text-sm gap-2">
                        🛒 <span class="bg-white/30 px-2 py-0.5 rounded-full font-black text-white">{{ $cartCount }}</span>
                    </a>
                </div>

                @if($products->isEmpty())
                    <div class="text-center py-24 glass-card border border-white/50 flex flex-col items-center justify-center">
                        <div class="text-7xl mb-6 grayscale opacity-40">🔍</div>
                        <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Yah, produknya gak ketemu</h3>
                        <p class="text-[var(--tx-text-muted)] font-bold mb-8">Coba ganti filter atau cari dengan kata kunci lain ya.</p>
                        <a href="{{ route('user.shop.index') }}" class="btn-pink">Lihat Semua Skincare</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="glass-card p-4 group hover:-translate-y-2 border border-white/50 flex flex-col h-full transition-all duration-300">
                                {{-- Foto --}}
                                <div class="relative overflow-hidden bg-white/40 rounded-[16px] aspect-square mb-5 border border-white/60">
                                    <img src="{{ $product->primary_image }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out"
                                         onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&q=80'">

                                    {{-- Badge kategori --}}
                                    <span class="absolute top-3 left-3 bg-[var(--tx-secondary)]/90 backdrop-blur-md text-white text-[9px] uppercase tracking-widest font-black px-3 py-1 rounded-full shadow-sm">
                                        {{ $product->category }}
                                    </span>

                                    {{-- Overlay habis --}}
                                    @if($product->stock <= 0)
                                        <div class="absolute inset-0 bg-white/60 backdrop-blur-md flex items-center justify-center">
                                            <span class="bg-red-500 text-white text-[10px] uppercase tracking-widest font-black px-4 py-1.5 rounded-full shadow-sm transform -rotate-12 border border-white">SOLD OUT</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex flex-col flex-grow px-1">
                                    <h3 class="text-sm font-black text-[var(--tx-text-dark)] leading-snug mb-2 line-clamp-2 group-hover:text-[var(--tx-secondary)] transition-colors">{{ $product->name }}</h3>

                                    {{-- Rating --}}
                                    @if($product->approvedReviews->count() > 0)
                                        <div class="flex items-center gap-1 mb-2">
                                            <span class="text-orange-400 text-sm">⭐</span>
                                            <span class="text-xs font-black text-[var(--tx-text-dark)]">{{ $product->average_rating }}</span>
                                            <span class="text-xs font-bold text-[var(--tx-text-muted)]">({{ $product->approvedReviews->count() }})</span>
                                        </div>
                                    @endif

                                    <div class="text-lg font-black text-[var(--tx-primary)] mb-1 mt-auto">{{ $product->formatted_price }}</div>
                                    
                                    <div class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-[9px] uppercase tracking-widest font-black px-2.5 py-1 rounded-full mb-5 w-max border border-amber-200">
                                        🪙 +{{ $product->estimated_coins }} XP
                                    </div>

                                    {{-- Stok --}}
                                    @if($product->stock > 0 && $product->stock <= 5)
                                        <div class="text-[9px] uppercase tracking-widest bg-red-100 text-red-600 font-black px-2.5 py-1 rounded-full mb-3 w-max">⚠️ Sisa {{ $product->stock }}</div>
                                    @endif

                                    {{-- Actions --}}
                                    <div class="flex gap-2 mt-auto">
                                        <a href="{{ route('user.shop.show', $product->slug) }}"
                                           class="w-12 h-12 flex items-center justify-center bg-white/60 text-[var(--tx-primary)] rounded-[14px] font-black hover:bg-[var(--tx-primary)] hover:text-white transition-all border border-white/50 shadow-sm">
                                            👁️
                                        </a>
                                        @if($product->stock > 0)
                                            <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit"
                                                    class="w-full h-12 flex items-center justify-center gap-2 text-xs font-black bg-[var(--tx-secondary)] text-white rounded-[14px] hover:bg-pink-500 transition-all shadow-md hover:shadow-pink-400/30">
                                                    + Tambah
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="flex-1 h-12 text-xs font-black bg-white/40 text-gray-400 rounded-[14px] cursor-not-allowed border border-white/50">Habis</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
