<x-app-layout>
    <x-slot name="title">Trinexa - Loyalty & Badges</x-slot>

    <div class="py-12 min-h-screen relative overflow-hidden">
        <!-- Dekorasi Orb -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl opacity-50 z-0 pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-gradient-to-tr from-[var(--tx-tertiary-light)] to-[var(--tx-pink)] rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl opacity-50 z-0 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-50/80 backdrop-blur-sm border border-green-200 text-green-700 px-4 py-3 rounded-2xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50/80 backdrop-blur-sm border border-red-200 text-red-700 px-4 py-3 rounded-2xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Hero Section (Level & Points) -->
            <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-lg border border-white/60 relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[var(--tx-primary)] via-[var(--tx-secondary)] to-[var(--tx-tertiary)]"></div>
                <div class="p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black mb-2">Tingkat Member</p>
                        <h3 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm">{{ $level }}</h3>
                        <p class="mt-2 text-[var(--tx-text-muted)] font-bold text-sm">Terus kumpulkan poin untuk membuka lebih banyak keuntungan eksklusif!</p>
                    </div>
                    <div class="mt-6 md:mt-0 text-center md:text-right">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black mb-2">Total Poin</p>
                        <h3 class="text-5xl font-black text-[var(--tx-text-dark)] drop-shadow-sm">{{ number_format($points) }} <span class="text-2xl text-[var(--tx-primary)]">pts</span></h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Login Streak -->
                <div class="col-span-1 glass-card rounded-[2rem] border border-white/60 p-6 shadow-md hover:shadow-lg transition-all hover:-translate-y-1">
                    <h4 class="text-sm uppercase tracking-widest font-black text-[var(--tx-text-dark)] mb-6 flex items-center gap-2">
                        <span class="text-[var(--tx-primary)] text-xl">🔥</span>
                        Login Streak
                    </h4>
                    <div class="flex items-center justify-center py-4">
                        <div class="text-center relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] opacity-10 rounded-full blur-xl"></div>
                            <span class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm relative z-10">{{ $streak ? $streak->current_streak : 0 }}</span>
                            <span class="block text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mt-2 relative z-10">Hari Berturut-turut</span>
                        </div>
                    </div>
                    <p class="text-xs text-center text-gray-500 mt-4 font-bold">Rekor Tertinggi: <span class="font-black text-[var(--tx-text-dark)]">{{ $streak ? $streak->highest_streak : 0 }}</span> hari</p>
                    <div class="mt-4 bg-[var(--tx-cream)]/50 border border-[var(--tx-primary)]/20 p-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-[var(--tx-primary)] text-center shadow-sm">
                        Terus login untuk dapatkan bonus poin!
                    </div>
                </div>

                <!-- Badges -->
                <div class="col-span-1 md:col-span-2 glass-card rounded-[2rem] border border-white/60 p-6 shadow-md">
                    <h4 class="text-sm uppercase tracking-widest font-black text-[var(--tx-text-dark)] mb-6 flex items-center gap-2">
                        <span class="text-[var(--tx-primary)] text-xl">🏅</span>
                        Koleksi Badge
                    </h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($badges as $badge)
                            @php
                                $isUnlocked = in_array($badge->id, $userBadgeIds);
                            @endphp
                            <div class="flex flex-col items-center p-4 rounded-2xl transition-all duration-300 {{ $isUnlocked ? 'bg-white/60 border border-white/80 shadow-sm hover:scale-105 hover:bg-white/80' : 'bg-gray-50/50 border border-gray-100 opacity-60 grayscale' }}">
                                <div class="w-16 h-16 rounded-full {{ $isUnlocked ? 'bg-gradient-to-br from-white to-[var(--tx-cream)] border border-white shadow-md' : 'bg-white shadow-sm' }} flex items-center justify-center mb-3">
                                    @if($badge->icon)
                                        <img src="{{ $badge->icon }}" alt="{{ $badge->name }}" class="w-10 h-10 object-contain drop-shadow-sm">
                                    @else
                                        <!-- Fallback icon -->
                                        <span class="text-2xl">{{ $isUnlocked ? '✨' : '🔒' }}</span>
                                    @endif
                                </div>
                                <span class="text-xs font-black text-center uppercase tracking-wider {{ $isUnlocked ? 'text-[var(--tx-text-dark)]' : 'text-gray-500' }}">{{ $badge->name }}</span>
                                <span class="text-[9px] font-bold text-center text-gray-500 mt-1.5 leading-tight">{{ $badge->description }}</span>
                                @if(!$isUnlocked)
                                    <svg class="w-3 h-3 text-gray-400 mt-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Vouchers & History -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Vouchers -->
                <div class="glass-card rounded-[2rem] border border-white/60 p-6 shadow-md h-full">
                    <h4 class="text-sm uppercase tracking-widest font-black text-[var(--tx-text-dark)] mb-6 flex items-center gap-2">
                        <span class="text-[var(--tx-primary)] text-xl">🎟️</span>
                        Tukar Voucher
                    </h4>
                    <div class="space-y-4">
                        @foreach($vouchers as $voucher)
                            <div class="border border-white/80 rounded-2xl p-5 flex flex-col sm:flex-row sm:justify-between sm:items-center bg-white/40 hover:bg-white/60 transition-all shadow-sm gap-4">
                                <div>
                                    <h5 class="font-black text-[var(--tx-text-dark)] text-sm mb-1">{{ $voucher->name }}</h5>
                                    <p class="text-[10px] font-bold text-[var(--tx-text-muted)] leading-relaxed">{{ $voucher->description }}</p>
                                    <div class="flex items-center gap-2 mt-3">
                                        <div class="text-[9px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-100 px-2 py-1 rounded-md shadow-sm">
                                            {{ number_format($voucher->points_required) }} pts
                                        </div>
                                        <div class="text-[9px] font-black uppercase tracking-widest text-[var(--tx-primary)] bg-[var(--tx-cream)] border border-[var(--tx-primary)]/20 px-2 py-1 rounded-md shadow-sm">
                                            Min: {{ $voucher->min_level }}
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('user.loyalty.redeem') }}" method="POST" class="shrink-0 w-full sm:w-auto">
                                    @csrf
                                    <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                    @php
                                        $voucherMinLevel = $voucher->min_level;
                                        if ($voucherMinLevel === 'Bronze') $voucherMinLevel = 'Member';
                                        if ($voucherMinLevel === 'Silver') $voucherMinLevel = 'Loyal';
                                        if ($voucherMinLevel === 'Gold') $voucherMinLevel = 'Premium';
                                        if ($voucherMinLevel === 'Platinum') $voucherMinLevel = 'VIP';

                                        $levelMap = ['Member' => 0, 'Loyal' => 1, 'Premium' => 2, 'VIP' => 3];
                                        $userLevelValue = $levelMap[$level] ?? 0;
                                        $minLevelValue = $levelMap[$voucherMinLevel] ?? 0;
                                        $isEligible = $userLevelValue >= $minLevelValue && $points >= $voucher->points_required;
                                    @endphp
                                    <button type="submit" 
                                            class="w-full sm:w-auto px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm {{ $isEligible ? 'btn-gradient hover:scale-105' : 'bg-gray-200/50 text-gray-400 cursor-not-allowed border border-gray-200' }}"
                                            {{ $isEligible ? '' : 'disabled' }}>
                                        Tukar Poin
                                    </button>
                                </form>
                            </div>
                        @endforeach
                        @if($vouchers->isEmpty())
                            <div class="bg-white/40 border border-white/60 rounded-2xl p-8 text-center">
                                <span class="text-4xl opacity-50 mb-3 block">🎫</span>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Belum ada voucher tersedia.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- History -->
                <div class="glass-card rounded-[2rem] border border-white/60 p-6 shadow-md h-full">
                    <h4 class="text-sm uppercase tracking-widest font-black text-[var(--tx-text-dark)] mb-6 flex items-center gap-2">
                        <span class="text-[var(--tx-primary)] text-xl">⏱️</span>
                        Riwayat Aktivitas
                    </h4>
                    <div class="space-y-1">
                        @foreach($history as $item)
                            <div class="flex justify-between items-center py-3 px-4 hover:bg-white/50 rounded-xl transition-colors border-b border-white/40 last:border-0">
                                <div>
                                    <p class="text-[11px] font-black text-[var(--tx-text-dark)] mb-0.5">{{ $item->description ?: ucfirst($item->activity_type) }}</p>
                                    <p class="text-[9px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">{{ $item->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="font-black text-sm px-3 py-1 rounded-lg {{ $item->points > 0 ? 'text-green-600 bg-green-50 border border-green-100 shadow-sm' : 'text-red-500 bg-red-50 border border-red-100 shadow-sm' }}">
                                    {{ $item->points > 0 ? '+' : '' }}{{ $item->points }}
                                </div>
                            </div>
                        @endforeach
                        @if($history->isEmpty())
                            <div class="bg-white/40 border border-white/60 rounded-2xl p-8 text-center mt-4">
                                <span class="text-4xl opacity-50 mb-3 block">📝</span>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Belum ada aktivitas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
