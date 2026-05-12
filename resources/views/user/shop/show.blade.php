<x-app-layout>
    <x-slot name="title">{{ $product->name }} | Naturea Trinexa</x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Flash --}}
        @if(session('success'))
            <div class="mb-6 bg-white/60 backdrop-blur-md border border-white/50 text-[var(--tx-quaternary)] font-black text-sm px-6 py-4 rounded-[16px] shadow-lg flex items-center gap-3">
                <span class="text-xl">🌟</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-white/60 backdrop-blur-md border border-white/50 text-red-500 font-black text-sm px-6 py-4 rounded-[16px] shadow-lg flex items-center gap-3">
                <span class="text-xl">⚠️</span> {{ session('error') }}
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-[var(--tx-text-muted)] mb-6 ml-2">
            <a href="{{ route('shop.index') }}" class="hover:text-[var(--tx-primary)] transition-colors">Naturea Store</a>
            <span class="opacity-50">/</span>
            <span class="bg-white/50 text-[var(--tx-text-dark)] border border-white/60 px-3 py-1 rounded-full shadow-sm backdrop-blur-sm">{{ $product->category }}</span>
            <span class="opacity-50">/</span>
            <span class="text-[var(--tx-text-dark)] truncate max-w-[200px]">{{ $product->name }}</span>
        </nav>

        {{-- MAIN PRODUCT CARD --}}
        <div class="glass-card border border-white/50 p-6 md:p-10 mb-10 shadow-xl shadow-[var(--tx-primary)]/5">
            <div class="flex flex-col md:flex-row gap-10 lg:gap-14">

                {{-- ── FOTO PRODUK ──────────────────────────────────────────── --}}
                <div x-data="{ activeImg: 0 }" class="w-full md:w-5/12 lg:w-4/12 shrink-0">
                    {{-- Main image --}}
                    <div class="relative bg-white/40 border border-white/80 rounded-[24px] overflow-hidden aspect-square mb-4 shadow-inner">
                        @php 
                            $imgs = !empty($product->images) ? $product->images : [$product->image_url ?? 'images/logo trinexa.jpeg']; 
                        @endphp

                        @foreach($imgs as $i => $img)
                            @php 
                                $url = filter_var($img, FILTER_VALIDATE_URL) ? $img : Storage::url($img);
                            @endphp
                            <img src="{{ $url }}" alt="{{ $product->name }}"
                                 x-show="activeImg === {{ $i }}"
                                 class="w-full h-full object-cover transition-transform duration-700 hover:scale-110"
                                 onerror="this.src='{{ asset('images/logo trinexa.jpeg') }}'">
                        @endforeach

                        @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-white/60 backdrop-blur-md flex items-center justify-center rounded-[24px]">
                                <span class="bg-red-500 text-white font-black px-6 py-2.5 rounded-full text-sm uppercase tracking-widest shadow-lg border border-white transform -rotate-12">SOLD OUT</span>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    @if(count($imgs) > 1)
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach($imgs as $i => $img)
                                @php 
                                    $url = filter_var($img, FILTER_VALIDATE_URL) ? $img : Storage::url($img);
                                @endphp
                                <button @click="activeImg = {{ $i }}"
                                    :class="activeImg === {{ $i }} ? 'border-[var(--tx-secondary)] shadow-md scale-105' : 'border-white/60 hover:border-[var(--tx-secondary-light)] opacity-70 hover:opacity-100'"
                                    class="w-16 h-16 bg-white/40 rounded-[12px] border-2 overflow-hidden shrink-0 transition-all">
                                    <img src="{{ $url }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('images/logo trinexa.jpeg') }}'">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── INFO PRODUK ──────────────────────────────────────────── --}}
                <div class="w-full md:w-7/12 lg:w-8/12 flex flex-col" x-data="productDetail({{ $product->id }}, {{ $product->stock }}, {{ json_encode($inCart) }})">

                    <div>
                        <div class="inline-flex items-center gap-2 bg-[var(--tx-quaternary-light)] border border-white text-[var(--tx-quaternary)] text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-4 shadow-sm backdrop-blur-sm">
                            ✨ NATUREA
                        </div>

                        <h1 class="text-3xl md:text-4xl font-black text-[var(--tx-text-dark)] leading-tight mb-3 drop-shadow-sm">{{ $product->name }}</h1>

                        {{-- Rating ringkas --}}
                        @if($reviewStats['total'] > 0)
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-orange-400 text-lg drop-shadow-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($reviewStats['average'])) ⭐ @else ✩ @endif
                                    @endfor
                                </div>
                                <span class="font-black text-sm text-[var(--tx-text-dark)]">{{ $reviewStats['average'] }}</span>
                                <span class="text-xs font-bold text-[var(--tx-text-muted)]">({{ $reviewStats['total'] }} ulasan)</span>
                            </div>
                        @endif

                        {{-- Harga --}}
                        <div class="flex items-baseline gap-4 mb-5">
                            <span class="text-4xl font-black text-[var(--tx-primary)] drop-shadow-sm">{{ $product->formatted_effective_price }}</span>
                            @if($product->is_bundle && $product->bundle_discount > 0)
                                <span class="text-lg font-bold text-gray-400 line-through">{{ $product->formatted_price }}</span>
                                <span class="bg-red-50 text-red-500 border border-red-200 text-[10px] uppercase tracking-widest font-black px-2 py-1 rounded-full shadow-sm">-{{ $product->bundle_discount }}%</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 mb-6">
                            <span class="text-[10px] bg-amber-100/80 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-full font-black uppercase tracking-widest shadow-sm">🪙 +{{ $product->estimated_coins }} XP</span>
                            @if($product->stock > 0)
                                <span class="text-[10px] font-black uppercase tracking-widest text-[var(--tx-text-muted)] bg-white/50 px-3 py-1.5 border border-white/60 rounded-full">Stok: <strong class="{{ $product->stock <= 5 ? 'text-red-500' : 'text-[var(--tx-text-dark)]' }}">{{ $product->stock }}</strong></span>
                            @else
                                <span class="text-[10px] font-black uppercase tracking-widest text-red-500 bg-red-50 px-3 py-1.5 border border-red-200 rounded-full">Habis</span>
                            @endif
                        </div>

                        <p class="text-[var(--tx-text-muted)] text-sm font-bold leading-relaxed mb-8 border-l-4 border-[var(--tx-secondary-light)] pl-4">{{ $product->short_description }}</p>
                    </div>

                    {{-- Add to cart logic --}}
                    <div class="mt-auto">
                        @if($product->stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="p-6 bg-white/30 backdrop-blur-sm border border-white/60 rounded-[20px] shadow-sm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="flex flex-col sm:flex-row gap-4 items-end">
                                    <div class="w-full sm:w-1/3">
                                        <label class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 block">Jumlah</label>
                                        <div class="flex items-center bg-white border border-white/80 rounded-[12px] overflow-hidden h-14 shadow-inner">
                                            <button type="button" @click="if(qty > 1) qty--" class="w-12 h-full bg-white text-[var(--tx-text-dark)] hover:bg-[var(--tx-secondary-light)] hover:text-[var(--tx-secondary)] font-black transition-colors">-</button>
                                            <input type="number" name="quantity" x-model="qty" min="1" :max="{{ $product->stock }}" class="w-full h-full text-center font-black text-[var(--tx-text-dark)] border-x border-gray-100 focus:outline-none appearance-none m-0">
                                            <button type="button" @click="if(qty < {{ $product->stock }}) qty++" class="w-12 h-full bg-white text-[var(--tx-text-dark)] hover:bg-[var(--tx-secondary-light)] hover:text-[var(--tx-secondary)] font-black transition-colors">+</button>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="w-full sm:w-2/3 h-14 text-sm btn-gradient shadow-md flex items-center justify-center gap-2">
                                        <span class="text-lg">🛒</span> + Keranjang
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-6 bg-white/30 backdrop-blur-sm border border-white/60 rounded-[20px] text-center shadow-sm">
                                <p class="text-[var(--tx-text-muted)] font-black text-sm mb-3">Yah, stoknya lagi habis nih.</p>
                                <button disabled class="w-full py-3.5 bg-white/50 text-gray-400 font-black text-[10px] uppercase tracking-widest rounded-[12px] cursor-not-allowed border border-white/60 shadow-inner">Stok Habis</button>
                            </div>
                        @endif

                        {{-- Keunggulan (Eco-friendly) --}}
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="flex items-center gap-3 p-3 bg-white/40 border border-white/60 rounded-[16px] backdrop-blur-sm shadow-sm hover:-translate-y-1 transition-transform group">
                                <div class="w-10 h-10 rounded-[10px] bg-green-100 flex items-center justify-center text-xl shrink-0 border border-white group-hover:scale-110 transition-transform">🌱</div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-black text-[var(--tx-text-dark)]">100% Organik</p>
                                    <p class="text-[9px] font-bold text-[var(--tx-text-muted)]">Aman untuk kulit</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-white/40 border border-white/60 rounded-[16px] backdrop-blur-sm shadow-sm hover:-translate-y-1 transition-transform group">
                                <div class="w-10 h-10 rounded-[10px] bg-[var(--tx-primary-light)] flex items-center justify-center text-xl shrink-0 border border-white group-hover:scale-110 transition-transform">♻️</div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-black text-[var(--tx-text-dark)]">Daur Ulang</p>
                                    <p class="text-[9px] font-bold text-[var(--tx-text-muted)]">Tukar di Karebla</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── TAB DESKRIPSI & ULASAN ─────────────────────────────────── --}}
        <div class="glass-card border border-white/50 p-6 md:p-10" x-data="{ tab: 'deskripsi' }">
            <div class="flex gap-6 border-b border-white/50 mb-8 pb-4">
                <button @click="tab = 'deskripsi'"
                        :class="tab === 'deskripsi' ? 'text-[var(--tx-secondary)] border-b-2 border-[var(--tx-secondary)]' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-secondary-mid)]'"
                        class="pb-2 font-black text-sm uppercase tracking-widest transition-all">
                    📝 Deskripsi
                </button>
                <button @click="tab = 'ulasan'"
                        :class="tab === 'ulasan' ? 'text-[var(--tx-secondary)] border-b-2 border-[var(--tx-secondary)]' : 'text-[var(--tx-text-muted)] hover:text-[var(--tx-secondary-mid)]'"
                        class="pb-2 font-black text-sm uppercase tracking-widest transition-all">
                    ⭐ Ulasan ({{ $reviewStats['total'] }})
                </button>
            </div>

            <div x-show="tab === 'deskripsi'" class="prose prose-sm max-w-none text-[var(--tx-text-dark)] font-bold leading-loose text-justify">
                {!! nl2br(e($product->description)) !!}
            </div>

            <div x-show="tab === 'ulasan'" style="display: none;">
                @if($reviews->isEmpty())
                    <div class="text-center py-16 bg-white/30 border border-white/60 rounded-[20px] backdrop-blur-sm">
                        <div class="text-5xl mb-4 opacity-50 grayscale drop-shadow-sm">💬</div>
                        <p class="text-[var(--tx-text-muted)] font-black text-sm">Belum ada ulasan untuk produk ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($reviews as $review)
                            <div class="bg-white/40 border border-white/60 rounded-[20px] p-6 shadow-sm backdrop-blur-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white font-black text-lg rounded-[14px] flex items-center justify-center border border-white/50 shadow-inner">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-[var(--tx-text-dark)]">{{ $review->user->name }}</p>
                                            <p class="text-[9px] font-bold uppercase tracking-widest text-[var(--tx-text-muted)] mt-0.5">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex text-orange-400 text-sm drop-shadow-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating) ⭐ @else ✩ @endif
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-[var(--tx-text-dark)] font-bold leading-relaxed">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productDetail', (id, maxStock, cartItem) => ({
                qty: 1,
                init() {
                    if (cartItem && cartItem.quantity) {
                        this.qty = 1; // Always start with 1 for adding MORE to cart, but we could cap it
                    }
                }
            }))
        })
    </script>
    @endpush
</x-app-layout>
