<x-app-layout>
    <x-slot name="title">Detail Pesanan | Naturea Trinexa</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('transaction.index') }}" class="text-[#D4AF37] hover:underline font-bold text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Riwayat
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 border border-red-200 p-4 rounded-2xl mb-6 font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Status & Resi --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Nomor Resi</div>
                    <div class="flex items-center gap-2">
                        <span id="receiptCode" class="text-xl md:text-2xl font-black text-[#0F2942] tracking-wider">{{ $transaction->receipt_number }}</span>
                        <button onclick="copyReceipt()" class="p-1.5 text-gray-400 hover:text-[#D4AF37] transition-colors rounded-full hover:bg-gray-50" title="Salin No Resi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                        <span id="copyToast" class="text-xs font-bold text-[#D4AF37] opacity-0 transition-opacity">Disalin!</span>
                    </div>
                    <div class="text-sm text-gray-400 mt-1">Dibuat pada {{ $transaction->created_at->translatedFormat('d M Y, H:i') }}</div>
                </div>

                @if($transaction->status === 'dibatalkan')
                    <div class="bg-red-50 text-red-600 border border-red-200 px-4 py-2 rounded-xl text-sm font-black uppercase tracking-wider">
                        DIBATALKAN
                    </div>
                @endif
            </div>

            {{-- Timeline Visual (hanya jika tidak dibatalkan) --}}
            @if($transaction->status !== 'dibatalkan')
                @php
                    $stages = ['pending' => 'Dibuat', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai'];
                    $currentIndex = array_search($transaction->status, array_keys($stages));
                @endphp
                <div class="relative pt-4">
                    <div class="absolute top-7 left-8 right-8 h-1 bg-gray-100 rounded-full"></div>
                    <div class="absolute top-7 left-8 h-1 bg-[#D4AF37] rounded-full transition-all duration-500" 
                         style="width: {{ $currentIndex === 0 ? '0' : ($currentIndex === 1 ? '33%' : ($currentIndex === 2 ? '66%' : '100%')) }};"></div>
                    
                    <div class="relative flex justify-between">
                        @foreach($stages as $key => $label)
                            @php
                                $index = array_search($key, array_keys($stages));
                                $isActive = $index <= $currentIndex;
                                $isCurrent = $index === $currentIndex;
                            @endphp
                            <div class="flex flex-col items-center w-16">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center mb-2 shadow-sm border-2 transition-colors duration-300 z-10
                                    {{ $isActive ? 'bg-[#D4AF37] border-[#D4AF37] text-white' : 'bg-white border-gray-200 text-gray-300' }}">
                                    @if($isActive)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                                    @endif
                                </div>
                                <div class="text-[10px] md:text-xs font-bold text-center {{ $isCurrent ? 'text-[#D4AF37]' : ($isActive ? 'text-[#0F2942]' : 'text-gray-400') }}">
                                    {{ $label }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl text-sm border border-red-100">
                    <span class="font-bold">Alasan Batal:</span> {{ $transaction->cancellation_reason ?? 'Dibatalkan oleh sistem / pengguna.' }}
                    <br><span class="text-xs mt-1 block">Dana sebesar Rp {{ number_format($transaction->total_amount, 0, ',', '.') }} telah dikembalikan ke saldo Harvestly Anda.</span>
                </div>
            @endif
        </div>

        {{-- Detail Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kiri: List Produk & Alamat --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Alamat --}}
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="font-extrabold text-[#0F2942] text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                        Alamat Pengiriman
                    </h3>
                    <div class="text-sm text-gray-700 bg-gray-50 p-4 rounded-2xl border border-gray-100 leading-relaxed">
                        <div class="font-bold text-gray-800 mb-1">{{ $transaction->user->name }}</div>
                        {{ $transaction->shipping_address }}
                    </div>
                </div>

                {{-- Daftar Produk --}}
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="font-extrabold text-[#0F2942] text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Daftar Produk
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($transaction->items as $item)
                            <div class="flex flex-col md:flex-row gap-4 border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                                <a href="{{ route('shop.show', $item->product->slug) }}" class="w-20 h-20 rounded-2xl bg-[#FDF8F0] overflow-hidden shrink-0 border border-gray-50 block hover:opacity-80 transition-opacity">
                                    <img src="{{ $item->product->primary_image }}" class="w-full h-full object-cover">
                                </a>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start gap-4">
                                            <a href="{{ route('shop.show', $item->product->slug) }}" class="font-extrabold text-[#0F2942] text-sm hover:text-[#D4AF37] transition-colors line-clamp-2">{{ $item->product->name }}</a>
                                            <div class="font-black text-[#0F2942] whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rp {{ number_format($item->price, 0, ',', '.') }} <span class="mx-1">x</span> {{ $item->quantity }}
                                            @if($item->variant) <span class="ml-2 bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->variant }}</span> @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Tombol Ulasan --}}
                                    @if($transaction->status === 'selesai')
                                        <div class="mt-3 text-right">
                                            @if(!$item->review)
                                                <a href="{{ url('/ulasan/buat/' . $item->id) }}" class="inline-block border border-[#D4AF37] text-[#D4AF37] text-xs font-bold px-4 py-1.5 rounded-lg hover:bg-[#D4AF37] hover:text-white transition-colors">
                                                    Tulis Ulasan
                                                </a>
                                            @else
                                                <span class="text-[10px] bg-gray-100 text-gray-500 font-bold px-2 py-1 rounded">Sudah Diulas</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Kanan: Ringkasan & Action --}}
            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm sticky top-24">
                    <h3 class="font-extrabold text-[#0F2942] text-lg mb-4">Rincian Pembayaran</h3>
                    
                    <div class="space-y-3 text-sm mb-4">
                        @php $subtotal = $transaction->items->sum(fn($i) => $i->price * $i->quantity); @endphp
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Subtotal Produk</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($transaction->discount_amount > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span>Diskon Voucher</span>
                                <span class="font-bold">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-[#0F2942]">Total Bayar</span>
                            <span class="font-black text-2xl text-[#D4AF37]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Info Koin --}}
                    @if($transaction->coins_earned > 0)
                        <div class="bg-[#2DD4A0]/10 rounded-xl p-3 flex items-center gap-3 border border-[#2DD4A0]/20 mb-6">
                            <span class="text-xl">🪙</span>
                            <div>
                                <div class="text-xs font-black text-[#2DD4A0]">+{{ $transaction->coins_earned }} Koin</div>
                                <div class="text-[10px] text-gray-600">
                                    {{ $transaction->status === 'selesai' ? 'Sudah ditambahkan ke dompet' : 'Akan masuk setelah pesanan selesai' }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Action Batalkan --}}
                    @if($transaction->status === 'pending')
                        <form action="{{ route('transaction.cancel', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Dana akan dikembalikan ke saldo Harvestly.')">
                            @csrf
                            <button type="submit" class="w-full bg-white border-2 border-red-100 text-red-500 font-bold py-3 rounded-xl hover:bg-red-50 hover:border-red-200 transition-colors text-sm">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function copyReceipt() {
            const code = document.getElementById('receiptCode').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const toast = document.getElementById('copyToast');
                toast.classList.remove('opacity-0');
                setTimeout(() => toast.classList.add('opacity-0'), 2000);
            });
        }
    </script>
    @endpush
</x-app-layout>
