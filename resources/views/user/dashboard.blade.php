<x-app-layout>
    <x-slot name="title">Dashboard | Trinexa</x-slot>

    <!-- 📦 KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-28 md:pb-12">
        
        <!-- Welcome Banner Keren -->
        <div class="glass-card w-full p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 relative overflow-hidden border border-white/50">
            <!-- Dekorasi Orb -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[var(--tx-primary-light)] via-[var(--tx-secondary-light)] to-transparent rounded-full -translate-y-1/2 translate-x-1/3 opacity-40"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-20 h-20 rounded-[20px] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-4xl font-extrabold shadow-lg border-2 border-white/50 transition-transform shrink-0 overflow-hidden">
                    @if(Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-[var(--tx-text-dark)] mb-1">{{ Auth::user()->name ?? 'User' }}</h2>
                    <p class="text-[var(--tx-text-muted)] font-bold text-sm">Selamat datang kembali di ekosistem Trinexa ✨</p>
                </div>
            </div>

            <!-- Topbar Stats (Badge) -->
            <div class="relative z-10 flex gap-3">
                <div class="flex items-center gap-2 bg-white/40 backdrop-blur-md text-[var(--tx-text-dark)] px-4 py-2.5 rounded-full border border-white/50 font-black shadow-sm">
                    <span class="text-lg text-[var(--tx-primary)]">⭐</span> 
                    <span>Member Level: <span class="text-[var(--tx-primary)]">{{ Auth::user()->loyalty_level }}</span></span>
                </div>
            </div>
        </div>

        <!-- Grid Layout for Widgets and Missions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            
            <!-- Kiri: 3 Widget & Eksplor -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 3 WIDGET UTAMA -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    
                    <!-- Saldo Harvestly -->
                    <div class="rounded-3xl p-6 relative overflow-hidden text-white shadow-xl shadow-[var(--tx-primary)]/20 transition-transform hover:-translate-y-1 bg-gradient-to-br from-[var(--tx-primary)] to-[#7BB3E8]">
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/20 rounded-full pointer-events-none"></div>
                        <div class="relative z-10 h-full flex flex-col">
                            <p class="font-bold text-sm text-white/90 mb-1">Saldo Harvestly</p>
                            <h3 class="text-3xl font-black mb-6">Rp 1.250.000</h3>
                            <div class="mt-auto">
                                <a href="{{ route('user.wallet.show') }}" class="inline-block bg-white/20 backdrop-blur-sm border border-white/40 text-white font-bold py-2 px-5 rounded-full text-xs hover:bg-white/30 transition-colors">
                                    Top Up
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Target Skincare -->
                    <div class="rounded-3xl p-6 relative overflow-hidden text-white shadow-xl shadow-[var(--tx-secondary)]/20 transition-transform hover:-translate-y-1 bg-gradient-to-br from-[var(--tx-secondary)] to-[#F8A4CF]">
                        <div class="relative z-10 h-full flex flex-col">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-xl">⚡</span>
                                <p class="font-bold text-sm text-white/90 truncate">Glow Serum Naturea</p>
                            </div>
                            <div class="flex items-end justify-between mb-2">
                                <h3 class="text-2xl font-black">Rp 85.000</h3>
                                <span class="text-xs font-black">65%</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden mt-auto">
                                <div class="bg-white h-full rounded-full w-[65%]"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Karebla Reward -->
                    <div class="rounded-3xl p-6 relative overflow-hidden text-white shadow-xl shadow-[var(--tx-quaternary)]/20 transition-transform hover:-translate-y-1 bg-gradient-to-br from-[var(--tx-quaternary)] to-[#9ECFC3]">
                        <div class="relative z-10 h-full flex flex-col">
                            <div class="text-3xl mb-3">💎</div>
                            <h3 class="text-lg font-black leading-tight mb-1">Penukaran Poin Eksklusif</h3>
                            <p class="text-[10px] text-white/90 mb-4 line-clamp-2">Tukar koin belanjamu dengan produk & botol eksklusif Karebla.</p>
                            <div class="mt-auto">
                                <a href="{{ route('karebla.index') }}" class="inline-block bg-white/20 backdrop-blur-sm border border-white/40 text-white font-bold py-2 px-4 rounded-full text-xs hover:bg-white/30 transition-colors">
                                    Lihat Katalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NAVIGASI CEPAT -->
                <div class="flex gap-4 overflow-x-auto no-scrollbar py-2 px-1">
                    <a href="{{ route('user.shop.index') }}" class="glass-card flex items-center gap-3 px-5 py-3 rounded-full hover:bg-white/50 transition-colors shrink-0 border border-white/50">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--tx-secondary)] to-[var(--tx-primary)] flex items-center justify-center text-white text-sm shadow-inner">🛍️</div>
                        <span class="font-bold text-[var(--tx-text-dark)] text-sm pr-2">Belanja</span>
                    </a>
                    <a href="{{ route('user.wallet.show') }}" class="glass-card flex items-center gap-3 px-5 py-3 rounded-full hover:bg-white/50 transition-colors shrink-0 border border-white/50">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-tertiary)] flex items-center justify-center text-white text-sm shadow-inner">💳</div>
                        <span class="font-bold text-[var(--tx-text-dark)] text-sm pr-2">Dompet</span>
                    </a>
                    <a href="{{ route('dermatology.index') }}" class="glass-card flex items-center gap-3 px-5 py-3 rounded-full hover:bg-white/50 transition-colors shrink-0 border border-white/50">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--tx-tertiary)] to-[var(--tx-secondary)] flex items-center justify-center text-white text-sm shadow-inner">🩺</div>
                        <span class="font-bold text-[var(--tx-text-dark)] text-sm pr-2">Dermatology</span>
                    </a>
                    <a href="{{ route('user.loyalty.index') }}" class="glass-card flex items-center gap-3 px-5 py-3 rounded-full hover:bg-white/50 transition-colors shrink-0 border border-white/50">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--tx-quaternary)] to-[var(--tx-primary)] flex items-center justify-center text-white text-sm shadow-inner">🎁</div>
                        <span class="font-bold text-[var(--tx-text-dark)] text-sm pr-2">Loyalty</span>
                    </a>
                </div>

                <!-- EKSPLOR PRODUK -->
                <div class="glass-card p-6 md:p-8 border border-white/50 rounded-[2rem]">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-[var(--tx-text-dark)]">Eksplor Produk</h3>
                        <a href="{{ route('user.shop.index') }}" class="text-[var(--tx-primary)] font-bold text-sm hover:text-[var(--tx-secondary)] transition-colors">Lihat Semua →</a>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($featuredProducts as $product)
                        <a href="{{ route('shop.show', $product->slug) }}" class="bg-white/60 border border-white/80 rounded-2xl p-3 flex flex-col hover:shadow-md transition-all cursor-pointer group">
                            <div class="w-full aspect-square bg-[var(--tx-secondary-light)] rounded-xl mb-3 flex items-center justify-center overflow-hidden transition-transform">
                                <img src="{{ $product->primary_image }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <span class="text-[9px] font-black text-white bg-[var(--tx-secondary)] px-2 py-0.5 rounded-full w-fit mb-1 shadow-sm">NATUREA</span>
                            <h4 class="font-bold text-[var(--tx-text-dark)] text-sm mb-1 line-clamp-1">{{ $product->name }}</h4>
                            <p class="font-black text-[var(--tx-primary)] text-sm mt-auto">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>
                        @endforeach
                        
                        @if(count($featuredProducts) === 0)
                            <div class="col-span-4 py-12 text-center text-[var(--tx-text-muted)] font-bold italic">
                                Belum ada produk tersedia ✨
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kanan: Misi Harian -->
            <div class="lg:col-span-1">
                <div class="glass-card border border-white/50 h-full flex flex-col p-6 rounded-[2rem]">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-[var(--tx-text-dark)]">Misi Harian 📋</h3>
                        <span class="text-xs font-bold text-[var(--tx-text-muted)]">Reset di 12j</span>
                    </div>

                    <div class="flex-grow space-y-3">
                        @if(isset($userMissions) && count($userMissions) > 0)
                            @foreach($userMissions as $um)
                                @if($um->is_completed)
                                    <!-- Misi Selesai -->
                                    <div class="bg-white/40 backdrop-blur-sm border border-white/50 rounded-2xl p-4 flex items-center gap-4 opacity-60">
                                        <div class="w-8 h-8 rounded-full bg-[var(--tx-quaternary)] text-white flex items-center justify-center text-sm shadow-sm shrink-0">✓</div>
                                        <div class="flex-grow">
                                            <h4 class="font-black text-[var(--tx-text-dark)] text-sm line-through">{{ $um->dailyMission->title }}</h4>
                                            <p class="text-xs text-[var(--tx-quaternary)] font-bold">+{{ $um->dailyMission->reward_xp }} XP</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Misi Belum Selesai -->
                                    <form action="{{ route('user.mission.complete', $um->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left bg-white/60 backdrop-blur-sm border border-white/80 rounded-2xl p-4 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all cursor-pointer group">
                                            <div class="w-8 h-8 rounded-full border-2 border-[var(--tx-primary)] text-transparent flex items-center justify-center text-sm group-hover:bg-[var(--tx-primary)] group-hover:text-white transition-colors shrink-0">✓</div>
                                            <div class="flex-grow">
                                                <h4 class="font-black text-[var(--tx-text-dark)] text-sm group-hover:text-[var(--tx-primary)] transition-colors">{{ $um->dailyMission->title }}</h4>
                                                <p class="text-xs text-[var(--tx-tertiary)] font-bold">+{{ $um->dailyMission->reward_xp }} XP</p>
                                            </div>
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center text-sm text-[var(--tx-text-muted)] py-8 font-medium bg-white/30 rounded-2xl border border-white/40 backdrop-blur-sm">Belum ada misi harian hari ini.</div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>

    </main>

</x-app-layout>
