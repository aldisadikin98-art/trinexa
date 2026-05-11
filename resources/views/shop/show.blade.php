<x-app-layout>
    <x-slot name="title">{{ $product->name }} - Belanja | Naturea</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12 relative">
        <!-- Dekorasi Orb -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl opacity-50 z-[-1]"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-gradient-to-tr from-[var(--tx-tertiary-light)] to-[var(--tx-pink)] rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl opacity-50 z-[-1]"></div>

        {{-- Breadcrumb --}}
        <nav class="flex text-[10px] font-black uppercase tracking-widest text-gray-400 mb-8 glass-card w-fit px-5 py-2 rounded-full border border-white/60">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="{{ route('shop.index') }}" class="hover:text-[var(--tx-primary)] transition-colors flex items-center gap-1"><span>🛍️</span> Shop</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('shop.index', ['category' => $product->category]) }}" class="hover:text-[var(--tx-primary)] transition-colors">{{ $product->category }}</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-[var(--tx-primary)] line-clamp-1 max-w-[150px]">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="glass-card border border-white/60 rounded-[2.5rem] p-4 md:p-8 flex flex-col lg:flex-row gap-8 lg:gap-12 relative z-10 shadow-lg">
            
            {{-- Bagian Kiri: Gambar Produk --}}
            <div class="w-full lg:w-[450px] shrink-0" x-data="{ mainImage: '{{ $product->primary_image }}' }">
                {{-- Main Image --}}
                <div class="aspect-square bg-white/60 border border-white/80 rounded-3xl overflow-hidden mb-4 relative shadow-sm">
                    <img :src="mainImage" alt="{{ $product->name }}" 
                         class="w-full h-full object-cover mix-blend-multiply"
                         onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80'">
                    
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <span class="bg-white/90 backdrop-blur-md text-[var(--tx-primary)] border border-white/50 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                            {{ $product->category }}
                        </span>
                    </div>
                </div>

                {{-- Gallery Thumbnails --}}
                @if(!empty($product->images) && is_array($product->images))
                    <div class="flex gap-3 overflow-x-auto no-scrollbar py-2">
                        @foreach($product->images as $img)
                            <button @click="mainImage = '{{ $img }}'" 
                                    class="w-20 h-20 shrink-0 bg-white/60 border border-white/80 rounded-2xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-[var(--tx-primary)] transition-all hover:scale-105 shadow-sm">
                                <img src="{{ $img }}" class="w-full h-full object-cover mix-blend-multiply" alt="Gallery">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Bagian Kanan: Detail & Info --}}
            <div class="flex-1 flex flex-col">
                <h1 class="text-3xl md:text-4xl font-black text-[var(--tx-text-dark)] leading-tight mb-4">
                    {{ $product->name }}
                </h1>
                
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-white/40">
                    <div class="text-3xl font-black text-[var(--tx-primary)] drop-shadow-sm">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <div class="bg-amber-50 border border-amber-100 text-amber-500 font-black text-xs px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-sm">
                        <span>⭐</span> +{{ floor($product->price / 10000) }} Koin
                    </div>
                </div>

                {{-- Status Stok --}}
                <div class="flex items-center gap-2 mb-8 bg-white/50 w-fit px-4 py-2 rounded-full border border-white shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full {{ $product->stock > 10 ? 'bg-green-500 animate-pulse' : ($product->stock > 0 ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                    <span class="text-xs font-black uppercase tracking-widest text-[var(--tx-text-dark)]">
                        @if($product->stock > 10)
                            Stok Tersedia: {{ $product->stock }}
                        @elseif($product->stock > 0)
                            Sisa {{ $product->stock }} (Hampir Habis)
                        @else
                            Stok Habis
                        @endif
                    </span>
                </div>

                {{-- Tab Deskripsi (Alpine.js) --}}
                <div x-data="{ activeTab: 'deskripsi' }" class="mb-8 flex-1">
                    <div class="flex border-b border-white/40 gap-6 mb-6 overflow-x-auto no-scrollbar text-[11px] font-black uppercase tracking-widest">
                        <button @click="activeTab = 'deskripsi'" 
                                :class="activeTab === 'deskripsi' ? 'text-[var(--tx-primary)] border-b-2 border-[var(--tx-primary)] pb-3' : 'text-gray-400 pb-3 hover:text-gray-600'">
                            Deskripsi
                        </button>
                        <button @click="activeTab = 'kandungan'" 
                                :class="activeTab === 'kandungan' ? 'text-[var(--tx-primary)] border-b-2 border-[var(--tx-primary)] pb-3' : 'text-gray-400 pb-3 hover:text-gray-600'">
                            Kandungan
                        </button>
                        <button @click="activeTab = 'cara_pakai'" 
                                :class="activeTab === 'cara_pakai' ? 'text-[var(--tx-primary)] border-b-2 border-[var(--tx-primary)] pb-3' : 'text-gray-400 pb-3 hover:text-gray-600'">
                            Cara Pakai
                        </button>
                        <button @click="activeTab = 'manfaat'" 
                                :class="activeTab === 'manfaat' ? 'text-[var(--tx-primary)] border-b-2 border-[var(--tx-primary)] pb-3' : 'text-gray-400 pb-3 hover:text-gray-600'">
                            Manfaat
                        </button>
                    </div>

                    <div class="prose prose-sm max-w-none text-gray-600 font-medium leading-relaxed">
                        <div x-show="activeTab === 'deskripsi'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                        <div x-show="activeTab === 'kandungan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            {!! nl2br(e($product->ingredients ?? 'Informasi kandungan belum tersedia.')) !!}
                        </div>
                        <div x-show="activeTab === 'cara_pakai'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            {!! nl2br(e($product->how_to_use ?? 'Cara pemakaian belum tersedia.')) !!}
                        </div>
                        <div x-show="activeTab === 'manfaat'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            {!! nl2br(e($product->benefits ?? 'Informasi manfaat belum tersedia.')) !!}
                        </div>
                    </div>
                </div>

                {{-- Action / Checkout Form (Floating di mobile, menyatu di desktop) --}}
                <div class="mt-auto bg-white/50 backdrop-blur-md p-5 rounded-3xl border border-white shadow-sm flex flex-col sm:flex-row gap-4 items-center">
                    
                    @if($product->stock > 0)
                        <div class="flex items-center gap-3 w-full sm:w-auto bg-white border border-gray-100 rounded-2xl p-1.5 shadow-inner">
                            <button type="button" onclick="document.getElementById('qty').stepDown()" class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-xl text-gray-600 hover:text-[var(--tx-primary)] font-black transition-colors">−</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center font-black text-[var(--tx-text-dark)] bg-transparent border-none focus:ring-0 p-0">
                            <button type="button" onclick="document.getElementById('qty').stepUp()" class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-xl text-gray-600 hover:text-[var(--tx-primary)] font-black transition-colors">+</button>
                        </div>

                        <div class="flex gap-3 w-full sm:w-auto flex-1">
                            <button type="button" 
                                    onclick="openBottomSheet('cart', {{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, '{{ $product->primary_image }}')"
                                    class="flex-1 bg-white border-2 border-[var(--tx-primary)] text-[var(--tx-primary)] font-black text-xs uppercase tracking-widest py-3.5 rounded-2xl hover:bg-[var(--tx-primary-light)] transition-all flex items-center justify-center gap-2">
                                <span>🛒</span> Keranjang
                            </button>
                            <button type="button" 
                                    onclick="openBottomSheet('checkout', {{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, '{{ $product->primary_image }}')"
                                    class="flex-1 btn-gradient font-black text-xs uppercase tracking-widest py-3.5 rounded-2xl shadow-lg hover:scale-105 transition-all flex items-center justify-center gap-2">
                                Beli Sekarang
                            </button>
                        </div>
                    @else
                        <div class="w-full bg-gray-100 text-gray-500 font-black text-xs uppercase tracking-widest py-4 rounded-2xl text-center border border-gray-200">
                            Maaf, produk sedang habis
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
