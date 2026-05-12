<x-app-layout>
    <x-slot name="title">Checkout | Naturea Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8 relative">
        <!-- Dekorasi Orb -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl opacity-50 z-[-1]"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-gradient-to-tr from-[var(--tx-tertiary-light)] to-[var(--tx-pink)] rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl opacity-50 z-[-1]"></div>

        <h1 class="text-2xl md:text-3xl font-black text-[var(--tx-text-dark)] mb-8 relative z-10">Checkout</h1>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 border border-red-200 p-4 rounded-2xl mb-8 font-bold text-sm relative z-10">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 relative z-10" x-data="checkoutApp({{ $subtotal }}, {{ $shipping_fee }}, {{ $wallet->balance }}, '{{ $user->address }}', {{ $userLoyaltyPoints }})">
            
            {{-- KOLOM KIRI --}}
            <div class="flex-1 space-y-6">
                
                {{-- 1. Alamat Pengiriman --}}
                <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[var(--tx-primary)] via-[var(--tx-secondary)] to-[var(--tx-tertiary)]"></div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-[var(--tx-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <h3 class="font-black text-[var(--tx-text-dark)] text-lg">Alamat Pengiriman</h3>
                    </div>

                    @if($user->address)
                        <div>
                            <div class="font-bold text-[var(--tx-text-dark)]">{{ $user->name }}</div>
                            <div class="text-[var(--tx-text-muted)] text-sm mb-2">{{ $user->email }}</div>
                            <div class="text-sm text-gray-700 bg-white/60 p-4 rounded-2xl border border-white/80 leading-relaxed shadow-sm">
                                {{ $user->address }}
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50/80 border border-red-100 rounded-2xl p-4 flex flex-col items-start gap-3 backdrop-blur-sm">
                            <div class="text-red-600 font-bold text-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Lengkapi alamat pengiriman untuk melanjutkan
                            </div>
                            <a href="{{ route('profile.edit') }}" class="bg-red-500 text-white font-black uppercase tracking-widest px-5 py-2.5 rounded-xl text-[10px] hover:bg-red-600 transition-colors shadow-sm border border-red-400">
                                Isi Alamat Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                {{-- 2. Daftar Produk --}}
                <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-lg">
                    <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[var(--tx-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Produk Dipesan
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($items as $item)
                            <div class="flex gap-4 border-b border-white/40 pb-4 last:border-0 last:pb-0">
                                <div class="w-20 h-20 rounded-2xl bg-white/60 overflow-hidden shrink-0 border border-white/80 relative shadow-sm">
                                    <img src="{{ $item->product->primary_image }}" class="w-full h-full object-cover mix-blend-multiply">
                                    <div class="absolute bottom-0 right-0 bg-[var(--tx-primary)] text-white text-[10px] font-black px-2 py-0.5 rounded-tl-xl border-l border-t border-white/50">x{{ $item->quantity }}</div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-[9px] font-black text-[var(--tx-primary)] bg-white/80 border border-white/50 px-2.5 py-1 rounded-full uppercase tracking-widest shadow-sm">{{ $item->product->category }}</span>
                                            <h4 class="font-black text-[var(--tx-text-dark)] text-sm line-clamp-2 mt-2">{{ $item->product->name }}</h4>
                                            @if($item->variant)
                                                <div class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-1 bg-white/50 w-fit px-2 py-0.5 rounded-md border border-white/50">Variasi: {{ $item->variant }}</div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="font-black text-[var(--tx-primary)]">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Ringkasan --}}
            <div class="lg:w-[400px] shrink-0">
                <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-lg sticky top-24">
                    
                    {{-- Voucher Section --}}
                    <div class="mb-6 pb-6 border-b border-white/40">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xl">🎟️</span>
                            <h3 class="font-black text-[var(--tx-text-dark)] text-sm uppercase tracking-widest">Voucher Naturea</h3>
                        </div>
                        
                        <div class="flex gap-2">
                            <input type="text" x-model="voucherInput" :disabled="isApplying" placeholder="Masukkan kode" class="flex-1 py-3 px-4 bg-white/50 backdrop-blur-sm border border-white/50 focus:border-[var(--tx-primary)] focus:ring-0 rounded-2xl text-sm font-bold uppercase shadow-sm">
                            <button @click="applyVoucher()" :disabled="isApplying || !voucherInput" class="btn-gradient disabled:opacity-50 disabled:cursor-not-allowed font-black px-6 rounded-2xl text-xs uppercase tracking-widest transition-colors shadow-sm">
                                <span x-show="!isApplying">Pakai</span>
                                <span x-show="isApplying">...</span>
                            </button>
                        </div>
                        <div x-show="voucherMsg" :class="voucherSuccess ? 'text-green-600' : 'text-red-500'" class="text-xs font-bold mt-2" x-text="voucherMsg" x-cloak></div>
                        <div x-show="activeVoucher" class="mt-2 text-[10px] font-black uppercase tracking-widest text-green-600 bg-green-50/80 border border-green-200 px-3 py-2 rounded-xl flex items-center gap-1 justify-between shadow-sm" x-cloak>
                            <span>✅ Voucher aktif.</span>
                            <button @click="removeVoucher" class="text-red-500 hover:text-red-700 bg-white px-2 py-1 rounded shadow-sm border border-red-100">Batal</button>
                        </div>

                        {{-- List User Vouchers --}}
                        @if($userVouchers->isNotEmpty())
                            <div class="mt-4 max-h-32 overflow-y-auto space-y-2 pr-2 scrollbar-hide">
                                @foreach($userVouchers as $v)
                                    <div @click="voucherInput = '{{ $v->code }}'" class="border border-white/60 bg-white/40 backdrop-blur-sm hover:bg-white/60 p-3 rounded-xl cursor-pointer transition-colors shadow-sm">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-black text-[var(--tx-text-dark)] text-xs">{{ $v->code }}</span>
                                            <span class="text-[10px] font-black text-white bg-green-500 px-2 py-0.5 rounded-full uppercase tracking-widest shadow-sm border border-green-400">{{ $v->type == 'percent' ? $v->value.'%' : 'Rp '.number_format($v->value, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-[10px] font-bold text-gray-500">Min. Blj: Rp {{ number_format($v->min_purchase, 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Loyalty Coins Switch --}}
                    <div class="mb-6 pb-6 border-b border-white/40 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-xl border border-amber-100 shadow-sm">🪙</div>
                            <div>
                                <h3 class="font-black text-[var(--tx-text-dark)] text-xs uppercase tracking-widest">Tukar Koin</h3>
                                <p class="text-[10px] font-bold text-gray-400">Saldo: {{ number_format($userLoyaltyPoints, 0, ',', '.') }} (Hemat <span x-text="formatRupiah(maxCoinsDiscount)"></span>)</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="useCoins" @change="calculateTotal" class="sr-only peer" :disabled="userCoins <= 0">
                            <div class="w-11 h-6 bg-gray-200/50 backdrop-blur-sm border border-white/50 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-[var(--tx-primary)] peer-checked:to-[var(--tx-secondary)] peer-disabled:opacity-50"></div>
                        </label>
                    </div>

                    {{-- Rincian Pembayaran --}}
                    <h3 class="font-black text-[var(--tx-text-dark)] text-sm uppercase tracking-widest mb-4">Rincian Pembayaran</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-[var(--tx-text-muted)] font-bold text-[10px] uppercase tracking-widest">Subtotal Produk</span>
                            <span class="font-black text-[var(--tx-text-dark)] text-sm">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-[var(--tx-text-muted)] font-bold text-[10px] uppercase tracking-widest">Ongkos Kirim (5%)</span>
                            <span class="font-black text-[var(--tx-text-dark)] text-sm">Rp {{ number_format($shipping_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-green-50/50 p-2 rounded-lg border border-green-100" x-show="discountAmount > 0" x-cloak>
                            <span class="text-green-600 font-black text-xs uppercase tracking-widest">Diskon Voucher</span>
                            <span class="font-black text-green-600">- <span x-text="formatRupiah(discountAmount)"></span></span>
                        </div>
                        
                        <div class="flex justify-between items-center bg-amber-50/50 p-2 rounded-lg border border-amber-100" x-show="useCoins && coinsDiscount > 0" x-cloak>
                            <span class="text-amber-600 font-black text-xs uppercase tracking-widest">Tukar Koin Loyalty</span>
                            <span class="font-black text-amber-500">- <span x-text="formatRupiah(coinsDiscount)"></span></span>
                        </div>
                        
                        <div class="pt-4 border-t border-white/40">
                            <div class="flex justify-between items-end mb-3">
                                <span class="font-black text-[var(--tx-text-dark)] text-xs uppercase tracking-widest">Total Pembayaran</span>
                                <span class="font-black text-2xl text-[var(--tx-primary)] drop-shadow-sm" x-text="formatRupiah(totalAfter)"></span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 backdrop-blur-sm p-3 rounded-xl border border-white/80 shadow-sm">
                                <span class="text-[10px] font-black uppercase tracking-widest text-[var(--tx-text-dark)]">Koin yang didapat</span>
                                <span class="text-xs font-black text-amber-500 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100" x-text="'⭐ +' + Math.floor(totalAfter / 10000)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Wallet Info --}}
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-5 mb-6 border border-white/80 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Saldo Harvestly Anda</span>
                            <span class="font-black text-[var(--tx-text-dark)]">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2 border-t border-white/40">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Sisa Saldo</span>
                            <span class="font-black text-sm" :class="walletBalance < totalAfter ? 'text-red-500' : 'text-green-600'" x-text="formatRupiah(walletBalance - totalAfter)"></span>
                        </div>

                        <div x-show="walletBalance < totalAfter" x-cloak class="mt-4 text-center">
                            <div class="text-[10px] text-red-500 font-black uppercase tracking-widest mb-2 bg-red-50 border border-red-100 py-1.5 rounded-lg shadow-sm">Saldo tidak mencukupi!</div>
                            <a href="{{ route('user.wallet.show') }}" class="block w-full text-center bg-white border-2 border-[var(--tx-primary)] text-[var(--tx-primary)] font-black uppercase tracking-widest px-3 py-2.5 rounded-xl hover:bg-[var(--tx-primary-light)] transition-colors text-[10px] shadow-sm">
                                Top Up Sekarang
                            </a>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <form action="{{ route('checkout.store') }}" method="POST" @submit="submitForm">
                        @csrf
                        <input type="hidden" name="mode" value="{{ $mode }}">
                        <input type="hidden" name="voucher_code" :value="activeVoucher">
                        <input type="hidden" name="item_ids" value="{{ $items->pluck('id')->join(',') }}">
                        <input type="hidden" name="use_coins" :value="useCoins ? '1' : '0'">

                        <button type="submit" 
                                :disabled="walletBalance < totalAfter || !hasAddress || isSubmitting"
                                :class="(walletBalance < totalAfter || !hasAddress || isSubmitting) ? 'bg-white/40 text-gray-400 cursor-not-allowed border border-white/60' : 'btn-gradient shadow-xl hover:scale-105 hover:shadow-2xl'"
                                class="w-full font-black text-xs uppercase tracking-widest py-4 rounded-2xl transition-all flex justify-center items-center gap-2">
                            <span x-show="!isSubmitting">Konfirmasi & Bayar</span>
                            <span x-show="isSubmitting">Memproses...</span>
                            <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutApp', (initSubtotal, initShipping, initWallet, userAddress, userLoyaltyPoints) => ({
                subtotal: initSubtotal,
                shipping: initShipping,
                walletBalance: initWallet,
                hasAddress: !!userAddress,
                userCoins: userLoyaltyPoints,
                useCoins: false,
                
                discountAmount: 0,
                coinsDiscount: 0,
                totalAfter: initSubtotal + initShipping,
                
                voucherInput: '',
                activeVoucher: '',
                voucherMsg: '',
                voucherSuccess: false,
                isApplying: false,
                isSubmitting: false,

                get maxCoinsDiscount() {
                    const tempTotal = this.subtotal + this.shipping - this.discountAmount;
                    return Math.min(this.userCoins, tempTotal);
                },

                calculateTotal() {
                    let temp = this.subtotal + this.shipping - this.discountAmount;
                    if (temp < 0) temp = 0;
                    
                    if (this.useCoins) {
                        this.coinsDiscount = Math.min(this.userCoins, temp);
                        temp -= this.coinsDiscount;
                    } else {
                        this.coinsDiscount = 0;
                    }
                    this.totalAfter = temp;
                },

                formatRupiah(amount) {
                    if (amount < 0) return '- Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.abs(amount));
                    return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(amount);
                },

                removeVoucher() {
                    this.activeVoucher = '';
                    this.voucherInput = '';
                    this.discountAmount = 0;
                    this.voucherMsg = '';
                    this.voucherSuccess = false;
                    this.calculateTotal();
                },

                async applyVoucher() {
                    if (!this.voucherInput || this.isApplying) return;
                    this.isApplying = true;
                    this.voucherMsg = '';

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                        const formData = new FormData();
                        formData.append('voucher_code', this.voucherInput);
                        formData.append('subtotal', this.subtotal);

                        const response = await fetch("{{ route('checkout.voucher') }}", {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.activeVoucher = this.voucherInput.toUpperCase();
                            this.discountAmount = data.discount_amount;
                            this.voucherSuccess = true;
                            this.voucherMsg = data.message;
                            this.calculateTotal();
                        } else {
                            this.removeVoucher();
                            this.voucherMsg = data.message;
                        }
                    } catch (err) {
                        this.removeVoucher();
                        this.voucherMsg = 'Terjadi kesalahan sistem.';
                    } finally {
                        this.isApplying = false;
                    }
                },

                submitForm(e) {
                    if (this.walletBalance < this.totalAfter || !this.hasAddress) {
                        e.preventDefault();
                        return;
                    }
                    this.isSubmitting = true;
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
