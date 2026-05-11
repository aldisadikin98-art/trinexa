<x-app-layout>
    <x-slot name="title">Detail Pesanan | Trinexa</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('transaction.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-[#D4AF37] font-bold transition-colors">
                <span>←</span> Kembali
            </a>
            <span class="px-4 py-1.5 rounded-full text-xs font-black {{ $transaction->status_color }}">
                {{ $transaction->status_label }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">❌ {{ session('error') }}</div>
        @endif

        {{-- Timeline Status (Simplified) --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-extrabold text-[#0F2942] mb-4">No. Resi: <span class="text-[#D4AF37]">{{ $transaction->receipt_number }}</span></h2>
            <div class="flex items-center text-sm text-gray-500 gap-4 mb-4">
                <span>📅 {{ $transaction->created_at->format('d M Y, H:i') }}</span>
                @if($transaction->tracking_number)
                    <span>🚚 Resi Kurir: <strong class="text-gray-800">{{ $transaction->tracking_number }}</strong></span>
                @endif
            </div>

            @if($transaction->status === 'dibatalkan')
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl text-sm">
                    <strong>Dibatalkan pada:</strong> {{ $transaction->cancelled_at?->format('d M Y, H:i') }}<br>
                    <strong>Alasan:</strong> {{ $transaction->cancellation_reason ?: 'Dibatalkan oleh sistem/user.' }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kiri: Daftar Produk --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-extrabold text-gray-800 mb-4">Daftar Produk</h3>
                    <div class="space-y-4">
                        @foreach($transaction->items as $item)
                            <div class="flex flex-col sm:flex-row gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                <img src="{{ $item->product->primary_image }}" class="w-20 h-20 rounded-xl object-cover shrink-0" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=200&q=80'">
                                <div class="flex-1">
                                    <a href="{{ route('shop.show', $item->product->slug) }}" class="font-bold text-gray-800 hover:text-[#D4AF37] transition-colors line-clamp-1">
                                        {{ $item->product->name }}
                                    </a>
                                    <div class="text-sm text-gray-500 mt-1">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                                <div class="font-black text-[#0F2942]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>

                                {{-- Ulasan Button (Jika Selesai) --}}
                                @if($transaction->canBeReviewed())
                                    <div class="shrink-0 sm:ml-4 flex items-center">
                                        @if($item->review)
                                            <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full">✓ Diulas</span>
                                        @else
                                            <button onclick="openReviewModal({{ $transaction->id }}, {{ $item->id }}, {{ $item->product->id }}, '{{ addslashes($item->product->name) }}', '{{ $item->product->primary_image }}')"
                                                class="text-xs font-bold bg-gray-100 text-[#0F2942] hover:bg-[#D4AF37] hover:text-white px-3 py-1.5 rounded-lg transition-colors">
                                                Tulis Ulasan
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Info Pengiriman --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-extrabold text-gray-800 mb-4">Informasi Pengiriman</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-bold text-gray-800">{{ $transaction->user->name }}</p>
                        <p>{{ $transaction->user->phone ?? '-' }}</p>
                        <p>{{ $transaction->shipping_address }}</p>
                    </div>
                </div>
            </div>

            {{-- Kanan: Rincian Pembayaran --}}
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-extrabold text-gray-800 mb-4">Rincian Pembayaran</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Metode Pembayaran</span>
                            <span class="font-bold text-gray-800">Harvestly Wallet</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($transaction->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        @if($transaction->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon Voucher</span>
                                <span class="font-bold">− Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <hr class="border-gray-100">
                        <div class="flex justify-between text-base">
                            <span class="font-extrabold text-gray-800">Total Belanja</span>
                            <span class="font-black text-[#D4AF37]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($transaction->coins_earned > 0)
                        <div class="mt-4 p-3 bg-green-50 border border-green-100 rounded-xl flex items-start gap-3">
                            <span class="text-xl">🪙</span>
                            <div>
                                <p class="text-sm font-bold text-green-700">+{{ $transaction->coins_earned }} Koin</p>
                                <p class="text-[10px] text-green-600 mt-0.5">Status: {{ $transaction->coins_status == 'credited' ? 'Berhasil ditambahkan' : 'Menunggu pesanan selesai' }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Batalkan Pesanan --}}
                @if($transaction->canBeCancelled())
                    <div class="bg-red-50 rounded-3xl shadow-sm border border-red-100 p-6">
                        <h3 class="font-extrabold text-red-800 mb-2">Batalkan Pesanan?</h3>
                        <p class="text-xs text-red-600 mb-4">Saldo akan dikembalikan utuh ke dompet Harvestly Anda.</p>
                        <form action="{{ route('transaction.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                            @csrf @method('PATCH')
                            <input type="hidden" name="reason" value="Dibatalkan oleh pembeli">
                            <button type="submit" class="w-full bg-red-100 text-red-700 font-bold py-2.5 rounded-xl hover:bg-red-200 transition-colors text-sm">
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Ulasan (Alpine.js) --}}
    <div x-data="{ open: false, trxId: null, itemId: null, prodId: null, prodName: '', prodImg: '', rating: 5 }"
         @open-review.window="
            open = true;
            trxId = $event.detail.trxId;
            itemId = $event.detail.itemId;
            prodId = $event.detail.prodId;
            prodName = $event.detail.prodName;
            prodImg = $event.detail.prodImg;
            rating = 5;
         "
         x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="open" @click="open = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-sm"></div>
            </div>

            <div x-show="open" class="inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-3xl relative z-10">
                <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="transaction_id" :value="trxId">
                    <input type="hidden" name="transaction_item_id" :value="itemId">
                    <input type="hidden" name="product_id" :value="prodId">
                    <input type="hidden" name="rating" :value="rating">

                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-[#FDF8F0]">
                        <h3 class="text-lg font-extrabold text-[#0F2942]">Tulis Ulasan</h3>
                        <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                    </div>

                    <div class="px-6 py-5 space-y-5">
                        {{-- Product Info --}}
                        <div class="flex gap-3 items-center">
                            <img :src="prodImg" class="w-12 h-12 rounded-lg object-cover">
                            <div class="font-bold text-sm text-gray-800" x-text="prodName"></div>
                        </div>

                        {{-- Rating --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Beri Penilaian</label>
                            <div class="flex gap-1 text-3xl cursor-pointer">
                                <template x-for="i in 5">
                                    <span @click="rating = i"
                                          class="transition-colors hover:scale-110"
                                          :class="i <= rating ? 'text-[#D4AF37]' : 'text-gray-300'">
                                        ★
                                    </span>
                                </template>
                            </div>
                        </div>

                        {{-- Jenis Kulit --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kulit Kamu (Opsional)</label>
                            <select name="skin_type" class="w-full border-gray-200 rounded-xl text-sm focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                                <option value="">Pilih jenis kulit...</option>
                                <option value="Normal">Normal</option>
                                <option value="Kering">Kering</option>
                                <option value="Berminyak">Berminyak</option>
                                <option value="Kombinasi">Kombinasi</option>
                                <option value="Sensitif">Sensitif</option>
                            </select>
                        </div>

                        {{-- Review Body --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ceritakan Pengalamanmu</label>
                            <textarea name="body" rows="4" required minlength="20" placeholder="Min. 20 karakter. Bagaimana teksturnya? Efeknya di kulitmu?"
                                class="w-full border-gray-200 rounded-xl text-sm focus:ring-[#D4AF37] focus:border-[#D4AF37]"></textarea>
                        </div>

                        {{-- Upload Foto --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload Foto (Opsional, Max 5)</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#D4AF37]/10 file:text-[#9a7c1f] hover:file:bg-[#D4AF37]/20 transition-colors">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-3xl">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-[#0F2942] rounded-xl hover:bg-[#1a3d5c]">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openReviewModal(trxId, itemId, prodId, prodName, prodImg) {
            window.dispatchEvent(new CustomEvent('open-review', {
                detail: { trxId, itemId, prodId, prodName, prodImg }
            }));
        }
    </script>
    @endpush
</x-app-layout>
