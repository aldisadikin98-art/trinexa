<x-app-layout>
    <x-slot name="title">Keranjang Belanja | Naturea Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8 relative">
        <!-- Dekorasi Orb -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl opacity-50 z-[-1]"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-gradient-to-tr from-[var(--tx-tertiary-light)] to-[var(--tx-pink)] rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl opacity-50 z-[-1]"></div>

        <h1 class="text-2xl md:text-3xl font-black text-[var(--tx-text-dark)] mb-8 relative z-10">Keranjang Belanja</h1>

        {{-- Toast Container --}}
        <div id="toast-container" class="fixed top-24 left-1/2 -translate-x-1/2 z-[100] flex flex-col gap-2 pointer-events-none"></div>

        @if($cartItems->isEmpty())
            {{-- STATE KOSONG --}}
            <div class="glass-card rounded-[2.5rem] p-12 text-center border border-white/60 shadow-lg max-w-2xl mx-auto relative z-10">
                <div class="text-8xl mb-6 opacity-80">🛒</div>
                <h2 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Keranjangmu kosong</h2>
                <p class="text-[var(--tx-text-muted)] font-bold mb-8">Belum ada produk skincare yang kamu masukkan. Yuk, eksplor koleksi Naturea sekarang!</p>
                <a href="{{ route('shop.index') }}" class="inline-block btn-gradient text-white font-black px-8 py-3.5 rounded-2xl transition-all shadow-lg hover:scale-105 uppercase tracking-widest text-sm">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8 relative z-10" x-data="cartApp({{ $walletBalance }})">
                
                {{-- KOLOM KIRI: Daftar Item --}}
                <div class="flex-1">
                    {{-- Header Pilih Semua --}}
                    <div class="bg-white/60 backdrop-blur-sm rounded-t-3xl p-5 border border-white/60 flex items-center gap-3">
                        <input type="checkbox" id="selectAll" x-model="selectAll" @change="toggleAll" class="w-5 h-5 text-[var(--tx-primary)] bg-white border border-gray-200 rounded focus:ring-[var(--tx-primary)]">
                        <label for="selectAll" class="font-black text-[var(--tx-text-dark)] cursor-pointer text-sm uppercase tracking-widest">Pilih Semua</label>
                    </div>

                    {{-- List Items --}}
                    <div class="bg-white/40 backdrop-blur-sm rounded-b-3xl border border-t-0 border-white/60 shadow-sm overflow-hidden">
                        @foreach($cartItems as $item)
                            <div class="p-5 border-b border-white/40 last:border-0 flex gap-4 transition-all hover:bg-white/50" id="cart-item-{{ $item->id }}" :class="{'opacity-50': isUpdating === {{ $item->id }}}">
                                
                                {{-- Checkbox --}}
                                <div class="pt-8 shrink-0">
                                    <input type="checkbox" value="{{ $item->id }}" x-model="selectedItems" @change="calculateSummary" 
                                           data-price="{{ $item->product->price }}" 
                                           id="checkbox-{{ $item->id }}"
                                           class="item-checkbox w-5 h-5 text-[var(--tx-primary)] bg-white border border-gray-200 rounded focus:ring-[var(--tx-primary)]"
                                           {{ $item->product->stock > 0 ? '' : 'disabled' }}>
                                </div>

                                {{-- Product Info --}}
                                <div class="flex flex-1 flex-col sm:flex-row gap-4">
                                    {{-- Image --}}
                                    <a href="{{ route('shop.show', $item->product->slug) }}" class="w-24 h-24 rounded-2xl bg-white/60 overflow-hidden shrink-0 border border-white/80 block relative shadow-sm">
                                        <img src="{{ $item->product->primary_image }}" class="w-full h-full object-cover mix-blend-multiply" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=200&q=80'">
                                        @if($item->product->stock == 0)
                                            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center">
                                                <span class="bg-gray-800 text-white text-[10px] font-black px-2 py-1 rounded-full uppercase tracking-wider shadow-sm">Habis</span>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Details --}}
                                    <div class="flex-1 flex flex-col">
                                        <div class="flex justify-between items-start mb-1">
                                            <div>
                                                <span class="text-[9px] font-black text-[var(--tx-primary)] bg-white/80 border border-white/50 px-2.5 py-1 rounded-full uppercase tracking-widest shadow-sm">{{ $item->product->category }}</span>
                                                @if($item->variant)
                                                    <span class="text-[9px] font-black text-gray-500 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-full ml-1">{{ $item->variant }}</span>
                                                @endif
                                            </div>
                                            <button @click="deleteItem({{ $item->id }})" class="text-red-400 hover:text-red-600 p-1 bg-white/50 rounded-lg transition-colors hover:bg-white" title="Hapus Item">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        
                                        <a href="{{ route('shop.show', $item->product->slug) }}" class="font-black text-[var(--tx-text-dark)] text-sm hover:text-[var(--tx-primary)] transition-colors line-clamp-2 mb-1">{{ $item->product->name }}</a>
                                        <div class="font-bold text-[var(--tx-text-muted)] text-xs mb-3">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>

                                        {{-- Actions Bottom --}}
                                        <div class="mt-auto flex items-end justify-between">
                                            @if($item->product->stock > 0)
                                                <div class="flex items-center gap-2 bg-white/60 border border-white/80 rounded-xl p-1 shadow-sm">
                                                    <button @click="updateQty({{ $item->id }}, -1, {{ $item->quantity }})" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-[var(--tx-primary)] font-black transition-colors">−</button>
                                                    <input type="number" id="qty-input-{{ $item->id }}" value="{{ $item->quantity }}" readonly class="w-8 p-0 text-center font-black text-[var(--tx-text-dark)] text-sm bg-transparent border-none focus:ring-0">
                                                    <button @click="updateQty({{ $item->id }}, 1, {{ $item->quantity }}, {{ $item->product->stock }})" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-[var(--tx-primary)] font-black transition-colors">+</button>
                                                </div>
                                            @else
                                                <div class="text-[10px] font-black uppercase tracking-widest text-red-500 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                                                    Stok Habis
                                                </div>
                                            @endif
                                            
                                            <div class="text-right">
                                                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Subtotal</div>
                                                <div class="font-black text-[var(--tx-primary)] text-sm item-subtotal" id="subtotal-display-{{ $item->id }}">
                                                    Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                </div>
                                                <input type="hidden" id="raw-subtotal-{{ $item->id }}" value="{{ $item->quantity * $item->product->price }}">
                                                <input type="hidden" id="raw-qty-{{ $item->id }}" value="{{ $item->quantity }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- KOLOM KANAN: Ringkasan Sticky --}}
                <div class="lg:w-[380px] shrink-0">
                    <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-lg sticky top-24">
                        <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-6 border-b border-white/40 pb-4">Ringkasan Belanja</h3>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-[var(--tx-text-muted)] font-bold text-sm">Total Harga (<span x-text="selectedItems.length"></span> barang)</span>
                                <span class="font-black text-[var(--tx-text-dark)]" x-text="formatRupiah(subtotal)"></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-[var(--tx-text-muted)] font-bold text-sm flex items-center gap-1">Estimasi Koin <span class="text-[9px] font-black uppercase tracking-widest bg-white/60 border border-white/80 px-1.5 rounded-md shadow-sm text-gray-500">1 koin / 10rb</span></span>
                                <span class="font-black text-amber-500 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100 text-sm" x-text="'⭐ +' + estimasiKoin"></span>
                            </div>

                            <div class="pt-4 border-t border-white/40">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="font-black text-[var(--tx-text-dark)] uppercase tracking-widest text-xs">Subtotal Pilihan</span>
                                    <span class="font-black text-xl text-[var(--tx-primary)] drop-shadow-sm" x-text="formatRupiah(subtotal)"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Wallet Check --}}
                        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 mb-6 border border-white/80 shadow-sm">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Saldo Harvestly</span>
                                <span class="font-black text-[var(--tx-text-dark)]">Rp {{ number_format($walletBalance, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- Warning Saldo Kurang --}}
                            <div x-show="subtotal > walletBalance" x-cloak class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-start gap-2 text-red-600 text-xs font-bold mb-3 bg-red-50/80 p-3 rounded-xl border border-red-100">
                                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>Saldo tidak cukup. Kurang <span x-text="formatRupiah(subtotal - walletBalance)"></span></span>
                                </div>
                                <a href="{{ route('user.wallet.show') }}" class="block w-full text-center bg-white border border-red-200 text-red-600 font-black uppercase tracking-widest py-3 rounded-xl hover:bg-red-50 hover:border-red-300 transition-colors text-[10px]">
                                    Top Up Saldo
                                </a>
                            </div>
                        </div>

                        {{-- Form Checkout --}}
                        <form action="{{ route('checkout.prepare') }}" method="POST">
                            @csrf
                            <template x-for="id in selectedItems" :key="id">
                                <input type="hidden" name="cart_item_ids[]" :value="id">
                            </template>

                            <button type="submit" 
                                    :disabled="selectedItems.length === 0 || subtotal > walletBalance"
                                    :class="selectedItems.length === 0 || subtotal > walletBalance ? 'bg-white/40 text-gray-400 cursor-not-allowed border border-white/60' : 'btn-gradient shadow-xl hover:scale-105 hover:shadow-2xl'"
                                    class="w-full font-black text-sm uppercase tracking-widest py-4 rounded-2xl transition-all flex justify-center items-center gap-2">
                                Lanjut Checkout
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartApp', (initialWallet) => ({
                walletBalance: initialWallet,
                selectedItems: [],
                selectAll: false,
                subtotal: 0,
                estimasiKoin: 0,
                isUpdating: null,

                init() {
                    const boxes = document.querySelectorAll('.item-checkbox:not(:disabled)');
                    this.selectedItems = Array.from(boxes).map(cb => cb.value);
                    this.selectAll = boxes.length > 0;
                    
                    this.$nextTick(() => { this.calculateSummary(); });
                },

                toggleAll() {
                    if (this.selectAll) {
                        const boxes = document.querySelectorAll('.item-checkbox:not(:disabled)');
                        this.selectedItems = Array.from(boxes).map(cb => cb.value);
                    } else {
                        this.selectedItems = [];
                    }
                    this.calculateSummary();
                },

                calculateSummary() {
                    let total = 0;
                    this.selectedItems.forEach(id => {
                        const rawInput = document.getElementById('raw-subtotal-' + id);
                        if (rawInput) {
                            total += parseInt(rawInput.value);
                        }
                    });
                    this.subtotal = total;
                    this.estimasiKoin = Math.floor(total / 10000);
                    
                    const boxes = document.querySelectorAll('.item-checkbox:not(:disabled)');
                    this.selectAll = boxes.length > 0 && this.selectedItems.length === boxes.length;
                },

                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
                },

                showToast(msg, type = 'success') {
                    const container = document.getElementById('toast-container');
                    if(!container) return;
                    const toast = document.createElement('div');
                    toast.className = `px-6 py-3 rounded-full shadow-lg font-black text-xs uppercase tracking-widest transform transition-all duration-300 translate-y-[-20px] opacity-0 flex items-center gap-2 border ${type === 'success' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200'}`;
                    toast.innerHTML = type === 'success' ? `✅ ${msg}` : `❌ ${msg}`;
                    container.appendChild(toast);
                    setTimeout(() => toast.classList.remove('translate-y-[-20px]', 'opacity-0'), 10);
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-[-20px]');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                },

                async updateQty(itemId, change, currentQty, maxStock = null) {
                    if (this.isUpdating) return;
                    
                    let newQty = currentQty + change;
                    if (newQty < 1) return;
                    if (maxStock !== null && newQty > maxStock) {
                        this.showToast(`Maksimal stok tersedia adalah ${maxStock}`, 'error');
                        return;
                    }

                    this.isUpdating = itemId;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                        const formData = new FormData();
                        formData.append('_method', 'PATCH');
                        formData.append('quantity', newQty);

                        const response = await fetch(`/keranjang/${itemId}`, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (!response.ok || !data.success) {
                            throw new Error(data.error || data.message || 'Gagal mengubah jumlah');
                        }

                        document.getElementById('qty-input-' + itemId).value = newQty;
                        document.getElementById('raw-qty-' + itemId).value = newQty;
                        document.getElementById('raw-subtotal-' + itemId).value = data.subtotal_item;
                        document.getElementById('subtotal-display-' + itemId).textContent = this.formatRupiah(data.subtotal_item);
                        
                        const btnMinus = document.querySelector(`#cart-item-${itemId} button:nth-child(1)`);
                        const btnPlus = document.querySelector(`#cart-item-${itemId} button:nth-child(3)`);
                        btnMinus.setAttribute('@click', `updateQty(${itemId}, -1, ${newQty}, ${maxStock})`);
                        btnPlus.setAttribute('@click', `updateQty(${itemId}, 1, ${newQty}, ${maxStock})`);

                        this.calculateSummary();
                        
                        const badge = document.getElementById('navCartBadge');
                        if (badge) badge.textContent = data.cart_count;

                    } catch (err) {
                        this.showToast(err.message, 'error');
                    } finally {
                        this.isUpdating = null;
                    }
                },

                async deleteItem(itemId) {
                    if (!confirm('Hapus produk ini dari keranjang?')) return;
                    if (this.isUpdating) return;
                    this.isUpdating = itemId;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');

                        const response = await fetch(`/keranjang/${itemId}`, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Gagal menghapus produk');
                        }

                        document.getElementById('cart-item-' + itemId).remove();
                        
                        this.selectedItems = this.selectedItems.filter(id => id != itemId);
                        this.calculateSummary();
                        this.showToast('Produk dihapus dari keranjang', 'success');

                        const badge = document.getElementById('navCartBadge');
                        if (badge) {
                            badge.textContent = data.cart_count;
                            if(data.cart_count === 0) badge.classList.add('hidden');
                        }

                        if (data.cart_count === 0) {
                            window.location.reload(); 
                        }

                    } catch (err) {
                        this.showToast(err.message, 'error');
                    } finally {
                        this.isUpdating = null;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
