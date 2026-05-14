<x-app-layout title="Penukaran Berhasil - Karebla">
    <div class="py-12 min-h-screen relative overflow-hidden z-10" x-data="kareblaSuccess">
        {{-- Confetti Canvas --}}
        <canvas id="confetti-canvas" class="fixed inset-0 z-50 pointer-events-none w-full h-full"></canvas>

        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-10 w-96 h-96 bg-gradient-to-bl from-[var(--tx-quaternary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-10 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 relative z-10 pt-10">
            <div class="glass-card rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-white/80 overflow-hidden bg-white/60 backdrop-blur-2xl animate-[slideUp_0.5s_ease-out]">
                
                {{-- Header Success --}}
                <div class="bg-gradient-to-br from-[var(--tx-quaternary)] to-[#7BB3E8] p-10 text-center relative overflow-hidden text-white group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000 pointer-events-none"></div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-inner border border-white/40 animate-[bounce_2s_infinite]">
                            <span class="text-5xl drop-shadow-sm">🎉</span>
                        </div>
                        <h2 class="text-3xl font-black mb-2 drop-shadow-sm">Penukaran Berhasil!</h2>
                        <p class="text-white/90 font-bold text-sm max-w-sm mx-auto">Terima kasih telah menukar koinmu. Hadiah eksklusif akan segera meluncur ke alamatmu!</p>
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="p-8 md:p-10">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 pb-8 border-b border-white/60 gap-4">
                        <div class="bg-white/40 px-5 py-3 rounded-[1.5rem] border border-white/60 shadow-sm">
                            <p class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1">No. Penukaran</p>
                            <p class="font-black text-[var(--tx-primary)] text-lg">{{ $redemption->receipt_number }}</p>
                        </div>
                        <div class="bg-white/40 px-5 py-3 rounded-[1.5rem] border border-white/60 shadow-sm sm:text-right">
                            <p class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1">Tanggal & Waktu</p>
                            <p class="font-black text-[var(--tx-text-dark)]">{{ $redemption->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-5 bg-white/50 p-5 rounded-[2rem] mb-8 border border-white/80 shadow-sm backdrop-blur-md">
                        <img src="{{ $redemption->product->primary_image }}" alt="{{ $redemption->product->name }}" class="w-24 h-24 rounded-2xl object-cover bg-white shadow-inner border border-gray-100 flex-shrink-0">
                        <div class="flex-1">
                            <h4 class="font-black text-[var(--tx-text-dark)] text-lg mb-2 leading-tight">{{ $redemption->product->name }}</h4>
                            <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm">-{{ number_format($redemption->coins_used, 0, ',', '.') }} <span class="text-[10px] text-[var(--tx-text-muted)] font-bold uppercase tracking-widest">Koin</span></span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-10">
                        <div class="glass-card bg-gradient-to-br from-[var(--tx-primary-light)]/50 to-[var(--tx-secondary-light)]/50 border border-white/60 p-6 rounded-[1.5rem] shadow-sm">
                            <h5 class="text-[10px] font-black text-[var(--tx-primary)] uppercase tracking-widest mb-2 flex items-center gap-2">
                                <span class="text-base">📍</span> Dikirim ke:
                            </h5>
                            <p class="text-sm text-[var(--tx-text-dark)] font-bold leading-relaxed">{{ $redemption->shipping_address }}</p>
                        </div>
                        <div class="bg-white/40 border border-white/60 p-6 rounded-[1.5rem] shadow-inner">
                            <h5 class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 flex items-center gap-2">
                                <span class="text-base">⏱️</span> Estimasi Pengiriman
                            </h5>
                            <p class="text-sm text-[var(--tx-text-dark)] font-black">2 - 3 Hari Kerja</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('karebla.history.detail', $redemption->id) }}" class="w-full text-center py-4 rounded-[1.5rem] font-black text-white bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] hover:-translate-y-1 transition-all shadow-lg shadow-[var(--tx-primary)]/30 uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                            <span>📦</span> Lacak Status
                        </a>
                        <a href="{{ route('karebla.index') }}" class="w-full text-center py-4 rounded-[1.5rem] font-black text-[var(--tx-text-muted)] bg-white border border-gray-200 shadow-sm hover:bg-gray-50 hover:text-[var(--tx-primary)] hover:border-[var(--tx-primary)]/30 transition-all uppercase tracking-widest text-xs">
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kareblaSuccess', () => ({
                init() {
                    // Trigger confetti
                    var duration = 4 * 1000;
                    var animationEnd = Date.now() + duration;
                    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 60 };

                    function randomInRange(min, max) {
                        return Math.random() * (max - min) + min;
                    }

                    var interval = setInterval(function() {
                        var timeLeft = animationEnd - Date.now();

                        if (timeLeft <= 0) {
                            return clearInterval(interval);
                        }

                        var particleCount = 50 * (timeLeft / duration);
                        // Trinexa aesthetic colors
                        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }, colors: ['#4A90D9', '#F472B6', '#9B8EC4', '#6BAE9B', '#ffffff'] }));
                        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }, colors: ['#4A90D9', '#F472B6', '#9B8EC4', '#6BAE9B', '#ffffff'] }));
                    }, 250);
                }
            }));
        });
    </script>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush
</x-app-layout>
