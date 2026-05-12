<x-app-layout>
    <x-slot name="title">Belanja Skincare Alami - Naturea</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8 relative">
        <!-- Dekorasi Orb -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[var(--tx-primary-light)] via-[var(--tx-secondary-light)] to-transparent rounded-full -translate-y-1/2 translate-x-1/3 opacity-40 z-[-1]"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[var(--tx-tertiary-light)] via-[var(--tx-pink)] to-transparent rounded-full translate-y-1/3 -translate-x-1/3 opacity-40 z-[-1]"></div>

        <div class="flex flex-col lg:flex-row gap-8 relative z-10">
            
            {{-- SIDEBAR KIRI (260px, Sticky) --}}
            <aside class="lg:w-[260px] shrink-0">
                <form id="filterForm" method="GET" action="{{ route('shop.index') }}" class="glass-card border border-white/60 p-6 sticky top-24 rounded-3xl">
                    
                    {{-- Search Bar --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Cari Produk</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama produk..."
                                   class="w-full pl-10 pr-4 py-3 bg-white/50 backdrop-blur-sm border border-white/50 focus:border-[var(--tx-primary)] focus:ring-0 rounded-2xl text-sm font-bold transition-all shadow-sm">
                            <span class="absolute left-3 top-3 text-gray-400">🔍</span>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-3">Kategori</label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="" onchange="document.getElementById('filterForm').submit()" 
                                       {{ !request('category') ? 'checked' : '' }} 
                                       class="w-5 h-5 text-[var(--tx-primary)] bg-white/50 border border-white focus:ring-[var(--tx-primary)] rounded">
                                <span class="text-sm font-bold text-gray-600 group-hover:text-[var(--tx-primary)] transition-colors">Semua Kategori</span>
                            </label>
                            @foreach(['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'] as $cat)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat }}" onchange="document.getElementById('filterForm').submit()"
                                           {{ request('category') === $cat ? 'checked' : '' }}
                                           class="w-5 h-5 text-[var(--tx-primary)] bg-white/50 border border-white focus:ring-[var(--tx-primary)] rounded">
                                    <span class="text-sm font-bold text-gray-600 group-hover:text-[var(--tx-primary)] transition-colors">{{ $cat }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Urutkan</label>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()" 
                                class="w-full py-3 px-4 bg-white/50 backdrop-blur-sm border border-white/50 focus:border-[var(--tx-primary)] focus:ring-0 rounded-2xl text-sm font-bold text-gray-700 cursor-pointer shadow-sm">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                            <option value="harga_terendah" {{ request('sort') == 'harga_terendah' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="harga_tertinggi" {{ request('sort') == 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full btn-gradient py-3.5 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-lg hover:scale-105">
                        Terapkan Filter
                    </button>
                </form>
            </aside>

            {{-- KONTEN KANAN --}}
            <div class="flex-1">
                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 glass-card p-5 rounded-3xl border border-white/60">
                    <div>
                        <h1 class="text-2xl font-black text-[var(--tx-text-dark)]">Katalog Naturea</h1>
                        <p class="text-sm font-bold text-[var(--tx-text-muted)] mt-1">Menampilkan <span class="font-black text-[var(--tx-primary)]">{{ $products->total() }}</span> produk eksklusif</p>
                    </div>
                    
                    {{-- Keranjang Badge --}}
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-2 bg-white/50 hover:bg-white/80 backdrop-blur-sm border border-white/50 text-[var(--tx-primary)] px-5 py-2.5 rounded-2xl font-black transition-all hover:scale-105 shadow-sm">
                        <span>🛒</span>
                        Keranjang
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-[var(--tx-secondary)] text-white text-xs font-black w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>

                {{-- Filter Aktif (Chips) --}}
                @if(request()->hasAny(['search', 'category']))
                    <div class="flex flex-wrap items-center gap-2 mb-6 bg-white/40 p-3 rounded-2xl border border-white/50 backdrop-blur-sm">
                        <span class="text-[10px] font-black text-gray-400 self-center uppercase tracking-widest mr-2">Filter Aktif:</span>
                        @if(request('search'))
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 text-[10px] font-black px-3 py-1.5 rounded-full hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors shadow-sm">
                                "{{ request('search') }}" <span class="text-xs">✕</span>
                            </a>
                        @endif
                        @if(request('category'))
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 text-[10px] font-black px-3 py-1.5 rounded-full hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors shadow-sm">
                                {{ request('category') }} <span class="text-xs">✕</span>
                            </a>
                        @endif
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center text-[10px] font-black text-[var(--tx-primary)] hover:text-[var(--tx-secondary)] self-center ml-2 transition-colors">Reset Semua →</a>
                    </div>
                @endif

                {{-- Grid Produk --}}
                @if($products->isEmpty())
                    <div class="glass-card border border-white/60 p-16 text-center rounded-3xl">
                        <div class="text-6xl mb-6">🍃</div>
                        <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Produk Tidak Ditemukan</h3>
                        <p class="text-gray-500 font-bold text-sm">Coba ubah kata kunci pencarian atau hapus filter yang aktif.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white/60 border border-white/60 p-4 rounded-[2rem] overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full">
                                {{-- Image Area --}}
                                <div class="relative aspect-square w-full bg-white/80 rounded-2xl overflow-hidden mb-4 border border-white/50">
                                    <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" 
                                         loading="lazy"
                                         class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500"
                                         onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&q=80'">
                                    
                                    {{-- Badges --}}
                                    <div class="absolute top-3 left-3 flex flex-col gap-2 items-start">
                                        <span class="bg-white/90 backdrop-blur-md text-[var(--tx-primary)] border border-white/50 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">
                                            {{ $product->category }}
                                        </span>
                                        @if($product->is_bundle)
                                            <span class="bg-[var(--tx-pink)] text-pink-700 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm border border-pink-200">
                                                🎁 Bundle
                                            </span>
                                        @endif
                                    </div>

                                    @if($product->stock == 0)
                                        <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                                            <span class="bg-gray-800 text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest shadow-lg border border-gray-600">
                                                Stok Habis
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Detail Area --}}
                                <div class="flex-1 flex flex-col px-2">
                                    <h3 class="font-black text-[var(--tx-text-dark)] text-base leading-snug mb-2 line-clamp-2 group-hover:text-[var(--tx-primary)] transition-colors">{{ $product->name }}</h3>
                                    
                                    <div class="mt-auto">
                                        <div class="flex items-center gap-1 mb-3">
                                            <span class="text-[10px] font-black text-amber-500 bg-amber-50 border border-amber-100 px-2 py-1 rounded-md flex items-center gap-1">
                                                ⭐ +{{ floor($product->price / 10000) }} Koin
                                            </span>
                                        </div>
                                        <div class="text-xl font-black text-[var(--tx-primary)] mb-5">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex gap-3 mt-auto">
                                            <a href="{{ route('shop.show', $product->slug) }}" 
                                               class="w-12 h-12 flex items-center justify-center bg-white/80 border border-white shadow-sm text-[var(--tx-primary)] rounded-2xl hover:bg-[var(--tx-primary-light)] transition-colors shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            
                                            @if($product->stock > 0)
                                                <button type="button" 
                                                        onclick="openBottomSheet('cart', {{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, '{{ $product->primary_image }}')"
                                                        class="flex-1 btn-gradient flex items-center justify-center gap-2 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:scale-105 transition-all shadow-md">
                                                    <span>🛒</span> Keranjang
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="flex-1 bg-gray-100 border border-gray-200 text-gray-400 flex items-center justify-center font-black text-[11px] uppercase tracking-widest rounded-2xl cursor-not-allowed">
                                                    Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10 bg-white/40 backdrop-blur-sm p-4 rounded-3xl border border-white/50">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
