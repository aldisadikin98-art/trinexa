<x-app-layout>
    <x-slot name="title">Riwayat Pesanan | Naturea Trinexa</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#0F2942] mb-8">Riwayat Pesanan</h1>

        {{-- Tabs --}}
        <div class="flex overflow-x-auto gap-2 border-b border-gray-200 mb-6 scrollbar-hide">
            @foreach($tabs as $key => $label)
                <a href="{{ route('transaction.index', ['status' => $key]) }}" 
                   class="whitespace-nowrap pb-3 px-4 text-sm font-bold transition-colors border-b-2 
                          {{ $status === $key ? 'border-[#D4AF37] text-[#D4AF37]' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if(session('success'))
            <div class="bg-green-50 text-green-600 border border-green-200 p-4 rounded-2xl mb-6 font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($transactions->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="text-8xl mb-6 opacity-80">📦</div>
                <h2 class="text-xl font-extrabold text-[#0F2942] mb-2">Belum ada pesanan</h2>
                <p class="text-gray-500 mb-6 text-sm">Ayo mulai belanja dan temukan produk favoritmu!</p>
                <a href="{{ route('shop.index') }}" class="inline-block bg-[#0F2942] text-white font-bold px-6 py-2.5 rounded-xl hover:bg-[#1a3d5c] transition-colors">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($transactions as $trx)
                    <div class="bg-white rounded-3xl p-5 md:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        
                        {{-- Header Card --}}
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-50">
                            <div>
                                <span class="font-black text-[#0F2942]">{{ $trx->receipt_number }}</span>
                                <span class="text-xs text-gray-400 block mt-0.5">{{ $trx->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            
                            @php
                                $badgeColor = match($trx->status) {
                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                                    'diproses' => 'bg-blue-50 text-blue-600 border-blue-200',
                                    'dikirim' => 'bg-purple-50 text-purple-600 border-purple-200',
                                    'selesai' => 'bg-green-50 text-green-600 border-green-200',
                                    'dibatalkan' => 'bg-red-50 text-red-600 border-red-200',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                                };
                            @endphp
                            <div class="border px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $badgeColor }}">
                                {{ $trx->status }}
                            </div>
                        </div>

                        {{-- Body Card --}}
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            @php $firstItem = $trx->items->first(); @endphp
                            @if($firstItem)
                                <div class="w-16 h-16 rounded-xl bg-[#FDF8F0] overflow-hidden shrink-0 border border-gray-50">
                                    <img src="{{ $firstItem->product->primary_image }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 flex flex-col justify-center">
                                    <h4 class="font-bold text-[#0F2942] text-sm line-clamp-1">{{ $firstItem->product->name }}</h4>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $firstItem->quantity }} barang
                                        @if($trx->items->count() > 1)
                                            <span class="text-[#D4AF37] font-bold ml-1">+{{ $trx->items->count() - 1 }} produk lainnya</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Footer Card --}}
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mt-4 pt-4 border-t border-gray-50">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Total Belanja</div>
                                <div class="font-black text-lg text-[#0F2942]">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="flex flex-col items-end gap-2 w-full md:w-auto">
                                @if($trx->coins_earned > 0)
                                    @if($trx->status == 'selesai')
                                        <div class="text-xs font-bold text-[#2DD4A0] bg-[#2DD4A0]/10 px-2 py-1 rounded-md">🪙 +{{ $trx->coins_earned }} Koin</div>
                                    @else
                                        <div class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-md border border-gray-200">🪙 Pending Koin</div>
                                    @endif
                                @endif
                                
                                <a href="{{ route('transaction.show', $trx->id) }}" class="w-full md:w-auto text-center border border-[#0F2942] text-[#0F2942] font-bold px-6 py-2 rounded-xl hover:bg-[#0F2942] hover:text-white transition-colors text-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
