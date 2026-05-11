<x-app-layout>
    <x-slot name="title">Checkout | Trinexa</x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8" x-data="checkout({{ $subtotal }}, {{ $wallet->balance ?? 0 }})">
        <h1 class="text-2xl font-extrabold text-[#0F2942] mb-6">✅ Konfirmasi Pesanan</h1>

        @if(session('error'))
            <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">❌ {{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" @submit="isLoading = true">
            @csrf

            {{-- Hidden item ids --}}
            @foreach($selected as $item)
                <input type="hidden" name="cart_item_ids[]" value="{{ $item->id }}">
            @endforeach

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- ── KIRI: Produk + Alamat ─────────────────────────── --}}
                <div class="lg:col-span-3 space-y-4">

                    {{-- Produk --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
                        <h3 class="font-extrabold text-gray-800 mb-4">Produk yang Dipesan</h3>
                        <div class="space-y-3">
                            @foreach($selected as $item)
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}"
                                         class="w-12 h-12 object-cover rounded-xl"
                                         onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=200&q=80'">
                                    <div class="flex-1">
                                        <div class="text-sm font-bold text-gray-800 line-clamp-1">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $item->product->category }} × {{ $item->quantity }}</div>
                                    </div>
                                    <div class="text-sm font-black text-[#0F2942]">Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-extrabold text-gray-800">📍 Alamat Pengiriman</h3>
                            <a href="{{ route('profile.edit') }}" class="text-xs text-[#D4AF37] hover:underline font-bold">Ubah</a>
                        </div>
                        @if($user->address)
                            <div class="bg-[#FDF8F0] rounded-2xl p-4">
                                <p class="font-bold text-sm text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $user->address }}</p>
                                @if($user->phone)
                                    <p class="text-xs text-gray-400 mt-1">📞 {{ $user->phone }}</p>
                                @endif
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-center">
                                <p class="text-sm text-red-600 font-bold mb-2">Alamat belum diisi!</p>
                                <a href="{{ route('profile.edit') }}" class="bg-red-500 text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-red-600 transition-colors">
                                    Lengkapi Profil
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Voucher --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
                        <h3 class="font-extrabold text-gray-800 mb-3">🎟️ Kode Voucher</h3>
                        <div class="flex gap-2">
                            <input type="text" id="voucherInput" name="voucher_code" x-model="voucherCode"
                                placeholder="Masukkan kode voucher"
                                class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/30 focus:border-[#D4AF37] uppercase"
                                :disabled="voucherApplied">
                            <button type="button" @click="checkVoucher()"
                                :disabled="!voucherCode || voucherApplied"
                                class="px-5 py-2.5 bg-[#D4AF37] text-white font-bold rounded-xl text-sm hover:bg-[#b8952d] transition-colors disabled:opacity-50">
                                <span x-text="voucherApplied ? '✓ Diterapkan' : 'Pakai'"></span>
                            </button>
                            <button type="button" x-show="voucherApplied" @click="removeVoucher()"
                                class="px-4 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl text-sm hover:bg-gray-200 transition-colors">✕</button>
                        </div>

                        <div x-show="voucherMessage" class="mt-2 text-sm font-medium px-1"
                             :class="voucherApplied ? 'text-green-600' : 'text-red-500'"
                             x-text="voucherMessage"></div>

                        <div x-show="voucherApplied" class="mt-3 bg-green-50 border border-green-200 rounded-xl p-3 text-sm">
                            <div class="font-bold text-green-700" x-text="voucherName"></div>
                            <div class="text-green-600">Diskon: <span x-text="discountFmt" class="font-black"></span></div>
                        </div>
                    </div>
                </div>

                {{-- ── KANAN: Ringkasan Pembayaran ───────────────────── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sticky top-24">
                        <h3 class="font-extrabold text-gray-800 mb-5">Rincian Pembayaran</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-green-600" x-show="discount > 0">
                                <span class="font-bold">Diskon Voucher</span>
                                <span class="font-black">− <span x-text="discountFmt"></span></span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between text-base">
                                <span class="font-extrabold text-gray-800">Total Bayar</span>
                                <span class="font-black text-[#D4AF37]">Rp <span x-text="total.toLocaleString('id-ID')"></span></span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Saldo Harvestly</span>
                                <span class="font-bold">Rp {{ number_format($wallet->balance ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Saldo setelah bayar</span>
                                <span class="font-bold" :class="balanceAfter < 0 ? 'text-red-500' : 'text-gray-700'">
                                    Rp <span x-text="Math.max(0, balanceAfter).toLocaleString('id-ID')"></span>
                                </span>
                            </div>
                            <div class="flex justify-between text-[#2DD4A0]">
                                <span class="font-bold">Est. Koin Didapat</span>
                                <span class="font-black">🪙 <span x-text="Math.floor(total / 10000)"></span></span>
                            </div>
                        </div>

                        {{-- Saldo kurang --}}
                        <div x-show="balanceAfter < 0" class="mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm font-medium">
                            ⚠️ Saldo kurang! Butuh Rp <span x-text="Math.abs(balanceAfter).toLocaleString('id-ID')" class="font-black"></span> lagi.
                            <a href="{{ route('user.wallet.topup') }}" class="block mt-1 font-bold text-red-600 hover:underline text-xs">→ Top Up Sekarang</a>
                        </div>

                        <div class="mt-5 p-3 bg-[#FDF8F0] rounded-xl text-xs text-gray-500">
                            💳 Pembayaran via <strong>Harvestly Wallet</strong>. Koin akan masuk setelah pesanan <strong>Selesai</strong>.
                        </div>

                        @if($user->address)
                            <button type="submit" form="checkoutForm"
                                :disabled="balanceAfter < 0 || isLoading"
                                class="w-full mt-5 font-bold py-4 rounded-2xl transition-all"
                                :class="balanceAfter >= 0 && !isLoading ? 'bg-[#0F2942] text-white hover:bg-[#1a3d5c]' : 'bg-gray-100 text-gray-400 cursor-not-allowed'">
                                <span x-show="!isLoading">🔒 Konfirmasi & Bayar</span>
                                <span x-show="isLoading" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                    Memproses...
                                </span>
                            </button>
                        @else
                            <button disabled class="w-full mt-5 font-bold py-4 rounded-2xl bg-gray-100 text-gray-400 cursor-not-allowed">
                                Lengkapi alamat dulu
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function checkout(subtotal, walletBalance) {
            return {
                subtotal,
                walletBalance,
                discount: 0,
                discountFmt: '',
                voucherCode: '',
                voucherApplied: false,
                voucherMessage: '',
                voucherName: '',
                isLoading: false,
                get total() { return Math.max(0, this.subtotal - this.discount); },
                get balanceAfter() { return this.walletBalance - this.total; },

                checkVoucher() {
                    if (!this.voucherCode) return;
                    fetch('/voucher/check', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ code: this.voucherCode, subtotal: this.subtotal }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.valid) {
                            this.discount      = data.discount;
                            this.discountFmt   = data.discount_fmt;
                            this.voucherApplied= true;
                            this.voucherName   = data.voucher_name + ' (' + data.type_label + ')';
                            this.voucherMessage= '✅ ' + data.message;
                        } else {
                            this.voucherMessage= data.message;
                            this.voucherApplied= false;
                        }
                    })
                    .catch(() => { this.voucherMessage = 'Gagal memvalidasi voucher.'; });
                },
                removeVoucher() {
                    this.discount = 0; this.discountFmt = ''; this.voucherApplied = false;
                    this.voucherMessage = ''; this.voucherCode = ''; this.voucherName = '';
                    document.querySelector('[name=voucher_code]').value = '';
                },
            }
        }
    </script>
    @endpush
</x-app-layout>
