<x-app-layout>
    <x-slot name="title">Pesanan Berhasil | Naturea Trinexa</x-slot>

    {{-- Animasi Sparkle CSS Murni --}}
    @push('scripts')
    <style>
        @keyframes pop {
            0% { transform: scale(0.8) translateY(20px); opacity: 0; }
            50% { transform: scale(1.1) translateY(-5px); opacity: 1; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(180deg); }
        }
        .animate-pop { animation: pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .sparkle-1 { animation: sparkle 2s ease-in-out infinite; animation-delay: 0.1s; }
        .sparkle-2 { animation: sparkle 2s ease-in-out infinite; animation-delay: 0.4s; }
        .sparkle-3 { animation: sparkle 2s ease-in-out infinite; animation-delay: 0.7s; }
        .sparkle-4 { animation: sparkle 2s ease-in-out infinite; animation-delay: 1.1s; }
    </style>
    @endpush

    <div class="max-w-3xl mx-auto px-4 py-12 flex flex-col items-center justify-center min-h-[70vh]">
        
        {{-- Ikon & Animasi --}}
        <div class="relative mb-6 animate-pop">
            <div class="w-24 h-24 bg-[#2DD4A0] rounded-full flex items-center justify-center shadow-[0_10px_30px_rgba(45,212,160,0.4)] animate-float relative z-10">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            {{-- Sparkles --}}
            <svg class="absolute -top-4 -left-4 w-6 h-6 text-[#D4AF37] sparkle-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
            <svg class="absolute top-2 -right-6 w-8 h-8 text-[#D4537E] sparkle-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
            <svg class="absolute -bottom-2 -left-8 w-5 h-5 text-[#2DD4A0] sparkle-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
            <svg class="absolute bottom-4 -right-4 w-4 h-4 text-[#0F2942] sparkle-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
        </div>

        <h1 class="text-3xl md:text-4xl font-black text-[#0F2942] mb-2 text-center">Pesanan Berhasil Dibuat! 🎉</h1>
        <p class="text-gray-500 text-center mb-8">Terima kasih telah berbelanja di Naturea.</p>

        {{-- Info Box --}}
        <div class="w-full bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm mb-8 animate-pop" style="animation-delay: 0.2s; opacity: 0;">
            
            <div class="text-center mb-6 pb-6 border-b border-gray-100 relative">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nomor Resi Transaksi</div>
                <div class="flex items-center justify-center gap-2">
                    <span id="receiptCode" class="text-xl md:text-2xl font-black text-[#D4AF37] tracking-wider">{{ $transaction->receipt_number }}</span>
                    <button onclick="copyReceipt()" class="p-2 text-gray-400 hover:text-[#0F2942] transition-colors rounded-full hover:bg-gray-50" title="Salin No Resi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
                <div id="copyToast" class="absolute -bottom-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-3 py-1 rounded-full opacity-0 transition-opacity">Disalin!</div>
            </div>

            <div class="space-y-4 mb-6">
                @foreach($transaction->items as $item)
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->product->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Qty: {{ $item->quantity }}</div>
                        </div>
                        <div class="font-black text-[#0F2942] text-sm text-right">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 flex justify-between items-center mb-4">
                <span class="font-bold text-gray-600">Total Pembayaran</span>
                <span class="font-black text-xl text-[#0F2942]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>

            @if($transaction->coins_earned > 0)
                <div class="flex items-center gap-3 bg-[#2DD4A0]/10 p-3 rounded-xl border border-[#2DD4A0]/20">
                    <div class="text-2xl">🪙</div>
                    <div>
                        <div class="font-black text-[#2DD4A0] text-sm">+{{ $transaction->coins_earned }} Koin</div>
                        <div class="text-xs text-gray-600">koin akan masuk otomatis setelah pesanan selesai.</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="w-full flex flex-col md:flex-row gap-4 animate-pop" style="animation-delay: 0.3s; opacity: 0;">
            <a href="{{ route('transaction.show', $transaction->id) }}" class="flex-1 border-2 border-[#0F2942] text-[#0F2942] font-extrabold py-3.5 rounded-2xl hover:bg-[#0F2942] hover:text-white transition-colors text-center shadow-sm">
                Lihat Pesanan
            </a>
            <a href="{{ route('shop.index') }}" class="flex-1 bg-[#D4AF37] text-white font-extrabold py-3.5 rounded-2xl hover:bg-[#b8952d] transition-all shadow-[0_4px_14px_0_rgba(212,175,55,0.39)] text-center">
                Belanja Lagi
            </a>
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
