<x-app-layout>
    <x-slot name="title">Keranjang Belanja | Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="cart()">

        <div class="flex items-center justify-between mb-8 glass-card px-8 py-5 border border-white/50">
            <h1 class="text-2xl font-black text-[var(--tx-text-dark)] flex items-center gap-3 drop-shadow-sm">
                <span class="bg-white/60 w-10 h-10 rounded-[12px] flex items-center justify-center border border-white shadow-sm text-[var(--tx-secondary)]">🛒</span> 
                Keranjang Belanja
            </h1>
            <a href="{{ route('shop.index') }}" class="text-[10px] uppercase tracking-widest font-black text-[var(--tx-primary)] hover:text-white bg-white/50 hover:bg-[var(--tx-primary)] border border-white/60 px-4 py-2 rounded-full transition-all shadow-sm">← Lanjut Belanja</a>
        </div>

        {{-- Flash --}}
        @if(session('success'))<div class="mb-8 flex items-center gap-3 bg-white/60 backdrop-blur-md border border-white/50 text-[var(--tx-quaternary)] font-black px-6 py-4 rounded-[16px] shadow-lg"><span class="text-xl">✅</span> {{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-8 flex items-center gap-3 bg-white/60 backdrop-blur-md border border-white/50 text-red-500 font-black px-6 py-4 rounded-[16px] shadow-lg"><span class="text-xl">❌</span> {{ session('error') }}</div>@endif

        @if($cartItems->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-24 glass-card border border-white/50">
                <div class="text-7xl mb-6 opacity-50 grayscale drop-shadow-md">🛒</div>
                <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Keranjang Kosong</h3>
                <p class="text-[var(--tx-text-muted)] font-bold mb-8">Belum ada produk di keranjangmu. Yuk, mulai belanja!</p>
                <a href="{{ route('shop.index') }}" class="btn-gradient shadow-xl">
                    Eksplor Produk Naturea
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ── ITEM LIST ─────────────────────────────────────────── --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Select All --}}
                    <div class="glass-card px-6 py-4 flex items-center gap-3 border border-white/50">
                        <input type="checkbox" id="selectAll" x-model="selectAll" @change="toggleAll()"
                            class="w-5 h-5 accent-[var(--tx-secondary)] rounded-[6px] border-white cursor-pointer shadow-sm">
                        <label for="selectAll" class="text-sm font-black text-[var(--tx-text-dark)] cursor-pointer uppercase tracking-widest pt-0.5">Pilih Semua ({{ $cartItems->count() }} produk)</label>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="glass-card p-5 flex items-start gap-5 border border-white/50 hover:bg-white/40 transition-colors group" x-data="cartItem({{ $item->id }}, {{ $item->product->price }}, {{ $item->quantity }}, {{ $item->product->stock }})">
                            {{-- Checkbox --}}
                            <input type="checkbox" :value="{{ $item->id }}" x-model="$root.selected"
                                class="mt-2 w-5 h-5 accent-[var(--tx-secondary)] rounded-[6px] border-white cursor-pointer shrink-0 shadow-sm">

                            {{-- Foto --}}
                            <a href="{{ route('shop.show', $item->product->slug) }}" class="shrink-0">
                                <div class="w-24 h-24 rounded-[16px] overflow-hidden border-2 border-white/80 shadow-md">
                                    <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=200&q=80'">
                                </div>
                            </a>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('shop.show', $item->product->slug) }}" class="font-black text-base text-[var(--tx-text-dark)] group-hover:text-[var(--tx-secondary)] transition-colors line-clamp-2 leading-tight">
                                    {{ $item->product->name }}
                                </a>
                                <div class="text-[9px] font-bold text-[var(--tx-text-muted)] mt-1 uppercase tracking-widest bg-white/50 inline-block px-2 py-0.5 rounded border border-white/60">{{ $item->product->category }}</div>
                                <div class="text-lg font-black text-[var(--tx-primary)] mt-1 drop-shadow-sm">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                <div class="inline-flex items-center gap-1.5 bg-amber-100/80 text-amber-700 text-[9px] uppercase tracking-widest font-black px-2 py-0.5 rounded-full mt-2 border border-amber-200 backdrop-blur-sm">🪙 +{{ $item->product->estimated_coins }} koin/item</div>
                            </div>

                            {{-- Qty + Subtotal + Hapus --}}
                            <div class="flex flex-col items-end gap-3 shrink-0 h-full justify-between">
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white/50 text-gray-400 hover:text-red-500 hover:bg-white rounded-[10px] transition-all border border-white/60 shadow-sm" title="Hapus">✕</button>
                                </form>

                                <div class="flex flex-col items-end gap-1">
                                    <div class="flex items-center gap-1.5 bg-white/60 rounded-[12px] p-1 border border-white shadow-sm backdrop-blur-sm">
                                        <button @click="decrease()" class="w-8 h-8 flex items-center justify-center bg-white rounded-[8px] text-[var(--tx-text-dark)] hover:text-[var(--tx-secondary)] font-black transition-colors shadow-sm">−</button>
                                        <span x-text="qty" class="w-8 text-center text-sm font-black text-[var(--tx-text-dark)]"></span>
                                        <button @click="increase()" class="w-8 h-8 flex items-center justify-center bg-white rounded-[8px] text-[var(--tx-text-dark)] hover:text-[var(--tx-secondary)] font-black transition-colors shadow-sm">+</button>
                                    </div>

                                    @if($item->product->stock <= 5 && $item->product->stock > 0)
                                        <div class="text-[9px] uppercase tracking-widest bg-red-100 text-red-600 font-black px-2 py-0.5 rounded-full border border-red-200 w-max mt-1">Sisa {{ $item->product->stock }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── RINGKASAN ─────────────────────────────────────────── --}}
                <div class="space-y-4">
                    <div class="glass-card p-6 lg:p-8 sticky top-24 border border-white/50 shadow-xl shadow-[var(--tx-secondary)]/10">
                        <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-6 flex items-center gap-3">
                            <span class="text-xl">🧾</span> Ringkasan Belanja
                        </h3>

                        <div class="space-y-4 text-sm bg-white/30 p-5 rounded-[20px] border border-white/60 backdrop-blur-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Produk dipilih</span>
                                <span class="font-black text-[var(--tx-text-dark)] bg-white/60 px-2 py-0.5 rounded shadow-sm" x-text="selected.length + ' item'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Subtotal</span>
                                <span class="font-black text-[var(--tx-primary)] text-lg">Rp <span x-text="subtotal.toLocaleString('id-ID')"></span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Est. Koin</span>
                                <span class="font-black text-amber-600 bg-amber-100/80 px-2 py-0.5 rounded border border-amber-200">🪙 +<span x-text="Math.floor(subtotal / 10000)"></span></span>
                            </div>
                            <hr class="border-white/60">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Saldo Harvestly</span>
                                <span class="font-black text-[var(--tx-text-dark)]">Rp {{ number_format($wallet?->balance ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Sisa Saldo</span>
                                <span class="font-black" :class="({{ $wallet?->balance ?? 0 }} - subtotal) < 0 ? 'text-red-500' : 'text-[var(--tx-quaternary)]'">
                                    Rp <span x-text="Math.max(0, {{ $wallet?->balance ?? 0 }} - subtotal).toLocaleString('id-ID')"></span>
                                </span>
                            </div>
                        </div>

                        {{-- Peringatan saldo kurang --}}
                        <div x-show="subtotal > {{ $wallet?->balance ?? 0 }} && selected.length > 0" class="mt-5 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-600 rounded-[16px] p-4 text-xs font-black shadow-sm">
                            ⚠️ Saldo Harvestly tidak cukup!
                            <a href="{{ route('user.wallet.topup') }}" class="block mt-2 font-black text-red-500 hover:text-white hover:bg-red-500 border border-red-500 rounded-full text-center py-2 transition-colors uppercase tracking-widest">Top Up Sekarang</a>
                        </div>

                        {{-- Checkout Form --}}
                        <form action="{{ route('checkout.index') }}" method="GET" class="mt-6">
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="items[]" :value="id">
                            </template>
                            <button type="submit"
                                :disabled="selected.length === 0"
                                class="w-full btn-gradient py-4 text-sm"
                                :class="selected.length > 0 ? '' : 'opacity-50 grayscale cursor-not-allowed'">
                                Checkout (<span x-text="selected.length"></span>)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function cart() {
            return {
                selected: {{ json_encode($cartItems->pluck('id')->toArray()) }},
                selectAll: true,
                prices: {{ json_encode($cartItems->mapWithKeys(fn($i) => [$i->id => $i->product->price])) }},
                quantities: {{ json_encode($cartItems->mapWithKeys(fn($i) => [$i->id => $i->quantity])) }},
                get subtotal() {
                    return this.selected.reduce((sum, id) => sum + (this.prices[id] || 0) * (this.quantities[id] || 0), 0);
                },
                toggleAll() {
                    this.selected = this.selectAll
                        ? {{ json_encode($cartItems->pluck('id')->toArray()) }}
                        : [];
                },
            }
        }

        function cartItem(id, price, qty, maxStock) {
            return {
                id, price, qty, maxStock,
                increase() {
                    if (this.qty < this.maxStock) {
                        this.qty++;
                        this.updateServer();
                        this.$root.quantities[this.id] = this.qty;
                    }
                },
                decrease() {
                    if (this.qty > 1) {
                        this.qty--;
                        this.updateServer();
                        this.$root.quantities[this.id] = this.qty;
                    }
                },
                updateServer() {
                    fetch(`/keranjang/${this.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'X-HTTP-Method-Override': 'PATCH',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ quantity: this.qty, _method: 'PATCH' }),
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
