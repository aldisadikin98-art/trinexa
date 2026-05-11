<x-app-layout>
    <x-slot name="title">Pesanan Berhasil! | Trinexa</x-slot>

    {{-- Confetti Canvas --}}
    <canvas id="confetti-canvas" class="fixed inset-0 pointer-events-none z-50"></canvas>

    <div class="max-w-2xl mx-auto px-4 py-12 text-center">

        {{-- Sparkle Animation --}}
        <div class="relative inline-block mb-6">
            <div class="w-24 h-24 bg-gradient-to-br from-[#D4AF37] to-[#F5E6C8] rounded-full flex items-center justify-center text-5xl shadow-xl animate-bounce">
                🎉
            </div>
            <div class="absolute -top-2 -right-2 text-2xl animate-ping">✨</div>
            <div class="absolute -bottom-2 -left-2 text-xl animate-ping delay-150">⭐</div>
        </div>

        <h1 class="text-3xl font-extrabold text-[#0F2942] mb-2">Pesanan Berhasil!</h1>
        <p class="text-gray-500 mb-8">Terima kasih sudah berbelanja di Naturea. Pesananmu sedang diproses.</p>

        {{-- Receipt Card --}}
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 mb-6 text-left">

            <div class="flex items-center justify-between mb-4 pb-4 border-b border-dashed border-gray-200">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">No. Resi</p>
                    <p class="font-black text-[#0F2942] text-lg">{{ $transaction->receipt_number }}</p>
                </div>
                <span class="bg-yellow-100 text-yellow-700 text-xs font-black px-3 py-1.5 rounded-full">⏳ Menunggu</span>
            </div>

            {{-- Produk --}}
            <div class="space-y-3 mb-4">
                @foreach($transaction->items as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product->primary_image }}" class="w-10 h-10 object-cover rounded-lg"
                             onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&q=80'">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $item->quantity }}× Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-sm font-black text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="space-y-2 text-sm border-t border-dashed border-gray-200 pt-4">
                @if($transaction->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon Voucher</span>
                        <span class="font-bold">− Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-extrabold text-base">
                    <span class="text-gray-800">Total Dibayar</span>
                    <span class="text-[#D4AF37]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Koin info --}}
            @if($transaction->coins_earned > 0)
                <div class="mt-4 bg-gradient-to-r from-[#2DD4A0]/10 to-[#2DD4A0]/5 border border-[#2DD4A0]/30 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#2DD4A0]/20 rounded-xl flex items-center justify-center text-xl">🪙</div>
                        <div>
                            <p class="font-black text-[#1aaa80] text-lg">+{{ $transaction->coins_earned }} koin</p>
                            <p class="text-xs text-gray-500">Koin akan masuk setelah pesanan <strong>Selesai</strong></p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4 text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $transaction->created_at->format('d M Y, H:i') }} WIB
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 justify-center">
            <a href="{{ route('transaction.show', $transaction) }}"
               class="flex-1 max-w-xs bg-[#0F2942] text-white font-bold py-3.5 rounded-2xl hover:bg-[#1a3d5c] transition-colors text-center">
                📋 Lihat Pesanan
            </a>
            <a href="{{ route('shop.index') }}"
               class="flex-1 max-w-xs border-2 border-[#D4AF37] text-[#D4AF37] font-bold py-3.5 rounded-2xl hover:bg-[#D4AF37] hover:text-white transition-colors text-center">
                🛍️ Belanja Lagi
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple confetti
        const canvas  = document.getElementById('confetti-canvas');
        const ctx     = canvas.getContext('2d');
        canvas.width  = window.innerWidth;
        canvas.height = window.innerHeight;

        const colors  = ['#D4AF37','#F8C8DC','#2DD4A0','#F5E6C8','#0F2942'];
        const pieces  = Array.from({length: 120}, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * -canvas.height,
            r: Math.random() * 6 + 3,
            d: Math.random() * 4 + 1,
            c: colors[Math.floor(Math.random() * colors.length)],
            tilt: Math.random() * 10 - 5,
        }));

        let frame = 0;
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            pieces.forEach(p => {
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.c;
                ctx.fill();
                p.y += p.d;
                p.x += Math.sin(frame / 20) * p.tilt;
                if (p.y > canvas.height) { p.y = -10; p.x = Math.random() * canvas.width; }
            });
            frame++;
            if (frame < 300) requestAnimationFrame(draw);
            else ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        draw();
    </script>
    @endpush
</x-app-layout>
