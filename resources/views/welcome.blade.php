<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trinexa - Super App Kecantikan & Keuangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animated Gradient Mesh Background (same as dashboard) */
        .bg-mesh-welcome {
            background: linear-gradient(135deg, #fce4f3, #f0e6ff, #e3f0ff, #e0f7f0, #fce4f3);
            background-size: 400% 400%;
            animation: meshShift 12s ease infinite;
        }
        @keyframes meshShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Smooth Float Animations */
        .float-slow { animation: float 6s ease-in-out infinite; }
        .float-medium { animation: float 5s ease-in-out infinite reverse; }
        .float-fast { animation: float 4s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0); }
        }

        /* Logo Card Shine */
        @keyframes logoShine {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .logo-shine {
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.6) 50%, transparent 100%);
            background-size: 200% auto;
            animation: logoShine 3s linear infinite;
        }
        
        /* Aura AI Core Animation */
        .aura-core { animation: pulseCore 3s ease-in-out infinite; }
        @keyframes pulseCore {
            0% { transform: scale(1); filter: drop-shadow(0 0 15px rgba(244, 114, 182, 0.4)); }
            50% { transform: scale(1.05); filter: drop-shadow(0 0 25px rgba(244, 114, 182, 0.6)); }
            100% { transform: scale(1); filter: drop-shadow(0 0 15px rgba(244, 114, 182, 0.4)); }
        }
    </style>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="text-[var(--tx-text-dark)] antialiased overflow-x-hidden min-h-screen relative selection:bg-[var(--tx-primary)]/20" style="background: linear-gradient(135deg, #fce4f3, #f0e6ff, #e3f0ff, #e0f7f0, #fce4f3); background-size: 400% 400%; animation: meshShift 12s ease infinite;">

    <!-- Subtle noise overlay for depth -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\"0 0 200 200\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"noise\"%3E%3CfeTurbulence type=\"fractalNoise\" baseFrequency=\"0.65\" numOctaves=\"3\" stitchTiles=\"stitch\"/%3E%3C/filter%3E%3Crect width=\"100%25\" height=\"100%25\" filter=\"url(%23noise)\" opacity=\"0.02\"/%3E%3C/svg%3E'); opacity: 0.4;"></div>

    <!-- 🌐 NAVBAR (Lebih Kontras & Cantik) -->
    <div class="fixed top-0 left-0 w-full z-50 pt-5 px-4 flex justify-center pointer-events-none">
        <nav class="w-full max-w-6xl h-16 bg-white/80 backdrop-blur-2xl border border-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-full flex justify-between items-center px-6 pointer-events-auto transition-all duration-300">
            
            <div class="flex-1 flex justify-start">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa Logo" class="w-10 h-10 object-cover rounded-[12px] shadow-sm group-hover:scale-105 transition-transform">
                    <h1 class="hidden sm:block text-2xl font-black text-[var(--tx-text-dark)] tracking-wider">TRINEXA</h1>
                </a>
            </div>

            <div class="hidden md:flex flex-1 justify-center items-center gap-8 font-black text-[11px] uppercase tracking-[0.2em] text-[var(--tx-text-muted)]">
                <a href="#beranda" class="hover:text-[var(--tx-primary)] transition-colors">Beranda</a>
                <a href="#fitur" class="hover:text-[var(--tx-primary)] transition-colors">Ekosistem</a>
                <a href="#aura-ai" class="hover:text-[var(--tx-primary)] transition-colors flex items-center gap-1.5 text-[var(--tx-text-dark)] bg-gray-100/50 px-3 py-1.5 rounded-full border border-gray-200/50">
                    <span class="text-sm">🤖</span> Aura AI
                </a>
            </div>

            <div class="flex-1 flex justify-end gap-3 items-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-gradient font-black text-xs uppercase tracking-widest py-2.5 px-6 rounded-xl shadow-md hover:scale-105 transition-transform">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden lg:block text-[var(--tx-text-dark)] hover:text-[var(--tx-primary)] font-black uppercase tracking-widest px-4 transition-colors text-xs">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-gradient font-black text-xs uppercase tracking-widest py-2.5 px-6 rounded-xl shadow-[0_4px_14px_0_rgba(244,114,182,0.39)] hover:shadow-[0_6px_20px_rgba(244,114,182,0.23)] hover:scale-105 transition-all">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </div>

    <!-- 🌟 SECTION 1: HERO (Bento Grid Style) -->
    <section id="beranda" class="relative pt-32 pb-24 lg:pt-40 lg:pb-32 overflow-hidden z-10 min-h-[90vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">
                
                <!-- Left: Text Content -->
                <div class="w-full lg:w-5/12 text-center lg:text-left pt-10 lg:pt-0">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/60 backdrop-blur-md border border-[var(--tx-primary)]/20 text-[var(--tx-primary)] text-[10px] uppercase tracking-[0.2em] font-black mb-6 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[var(--tx-primary)] animate-pulse"></span> Super App Masa Depan
                    </div>
                    
                    <h1 class="text-5xl lg:text-6xl font-black tracking-tight mb-6 leading-[1.15] text-[var(--tx-text-dark)]">
                        Kecantikan &<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] via-[var(--tx-secondary)] to-purple-500 drop-shadow-sm">Gaya Hidup.</span><br>
                        Dalam Satu Genggaman.
                    </h1>
                    
                    <p class="text-base font-bold text-[var(--tx-text-muted)] mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        Nikmati pengalaman berbelanja skincare terbaik, konsultasi AI instan, dan atur keuangan digitalmu dalam satu ekosistem premium yang mulus tanpa hambatan.
                    </p>

                    <!-- Input & Button -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full max-w-md mx-auto lg:mx-0 relative z-30">
                        <input type="email" placeholder="Masukkan email..." class="w-full sm:w-auto flex-1 px-5 py-4 rounded-2xl border-2 border-white bg-white/80 backdrop-blur-xl text-[var(--tx-text-dark)] focus:outline-none focus:border-[var(--tx-primary)] placeholder-gray-400 font-bold transition-all shadow-sm">
                        <a href="{{ route('register') }}" class="btn-gradient w-full sm:w-auto px-8 py-4 rounded-2xl text-xs uppercase tracking-widest font-black shadow-lg shadow-[var(--tx-primary)]/20 hover:scale-105 transition-transform flex items-center justify-center">
                            Mulai Sekarang
                        </a>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-center lg:justify-start gap-4">
                        <div class="flex -space-x-3">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" class="w-10 h-10 rounded-full border-2 border-white object-cover">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=100&auto=format&fit=crop" class="w-10 h-10 rounded-full border-2 border-white object-cover">
                            <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?q=80&w=100&auto=format&fit=crop" class="w-10 h-10 rounded-full border-2 border-white object-cover">
                        </div>
                        <div class="text-xs font-bold text-[var(--tx-text-muted)]">
                            <span class="text-[var(--tx-text-dark)] font-black">10K+</span> Pengguna Aktif
                        </div>
                    </div>
                </div>

                <!-- Right: Bento Grid Showcase -->
                <div class="w-full lg:w-7/12 relative min-h-[500px] flex items-center justify-center">
                    <!-- Base glow -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-gradient-to-tr from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
                    
                    <div class="relative w-full max-w-[600px] aspect-[4/3] grid grid-cols-12 grid-rows-12 gap-4">

                        <!-- 🏷️ LOGO BRAND CARD (Paling Atas, Full Width) -->
                        <div class="col-span-12 row-span-3 glass-card bg-white/70 backdrop-blur-2xl border border-white/90 rounded-[2rem] px-8 shadow-xl float-slow flex items-center justify-between relative overflow-hidden group">
                            <!-- Animated gradient shine -->
                            <div class="absolute inset-0 logo-shine pointer-events-none opacity-60 group-hover:opacity-80 transition-opacity"></div>
                            <!-- Left orb -->
                            <div class="absolute -left-10 -top-10 w-32 h-32 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-full blur-3xl opacity-20 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                            <!-- Right orb -->
                            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-gradient-to-tl from-[var(--tx-quaternary)] to-[var(--tx-tertiary)] rounded-full blur-3xl opacity-20 pointer-events-none"></div>

                            <div class="flex items-center gap-4 z-10 relative">
                                <div class="relative">
                                    <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa" class="w-12 h-12 rounded-2xl object-cover shadow-lg border-2 border-white group-hover:scale-110 transition-transform duration-300">
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm animate-pulse"></div>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-[var(--tx-text-dark)] tracking-wider leading-none">TRINEXA</h2>
                                    <p class="text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-[0.2em] mt-0.5">Super App · Kecantikan & Finansial</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 z-10 relative">
                                @foreach(['💳', '🧴', '🤖', '🩺'] as $icon)
                                <div class="w-9 h-9 rounded-xl bg-white/80 border border-white shadow-sm flex items-center justify-center text-base hover:scale-110 transition-transform cursor-default">{{ $icon }}</div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Harvestly Wallet (Top Left) -->
                        <div class="col-span-7 row-span-6 glass-card bg-white/70 backdrop-blur-xl border border-white/80 rounded-[2.5rem] p-6 shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] float-slow flex flex-col justify-between overflow-hidden group">
                            <div class="absolute -right-10 -top-10 w-32 h-32 bg-gradient-to-br from-[var(--tx-secondary-light)] to-[var(--tx-secondary)] rounded-full opacity-20 blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                            <div>
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-gray-100 mb-4">💳</div>
                                <h3 class="text-lg font-black text-[var(--tx-text-dark)]">Harvestly</h3>
                                <p class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest mt-1">Dompet Pintar</p>
                            </div>
                            <div class="mt-4">
                                <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Saldo Aktif</div>
                                <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-secondary)] to-[var(--tx-primary)]">Rp 2.450k</div>
                            </div>
                        </div>

                        <!-- Naturea Shop (Top Right) -->
                        <div class="col-span-5 row-span-7 glass-card bg-gradient-to-b from-white/90 to-white/40 backdrop-blur-xl border border-white/80 rounded-[2.5rem] p-6 shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] float-fast flex flex-col items-center text-center overflow-hidden relative">
                            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=400&auto=format&fit=crop')] bg-cover bg-center opacity-[0.03]"></div>
                            <div class="w-14 h-14 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-[1.25rem] flex items-center justify-center text-white text-2xl shadow-lg shadow-[var(--tx-primary)]/30 mb-4 z-10">🧴</div>
                            <h3 class="text-lg font-black text-[var(--tx-text-dark)] z-10">Naturea</h3>
                            <p class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest mt-1 mb-4 z-10">Premium Skincare</p>
                            
                            <div class="w-full bg-white/80 border border-white rounded-2xl p-3 mt-auto shadow-sm z-10 text-left flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden shrink-0"><img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=100&auto=format&fit=crop" class="w-full h-full object-cover"></div>
                                <div>
                                    <div class="text-[9px] font-black uppercase text-[var(--tx-primary)]">Trending</div>
                                    <div class="text-xs font-black text-gray-800 truncate">Glowing Serum</div>
                                </div>
                            </div>
                        </div>

                        <!-- Aura AI (Bottom Spanning) -->
                        <div class="col-span-12 row-span-6 lg:col-span-7 lg:row-span-6 glass-card bg-[#0F2942] backdrop-blur-xl border border-white/20 rounded-[2.5rem] p-6 shadow-[0_30px_60px_-15px_rgba(15,41,66,0.3)] float-medium flex items-center gap-5 overflow-hidden relative">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-[var(--tx-primary)] to-purple-500 rounded-full opacity-30 blur-2xl"></div>
                            
                            <div class="w-16 h-16 bg-white/10 border border-white/20 rounded-[1.5rem] flex items-center justify-center text-3xl shadow-inner backdrop-blur-md aura-core shrink-0">🤖</div>
                            
                            <div class="flex-1 relative z-10">
                                <div class="inline-block px-2 py-1 rounded bg-white/10 text-[8px] font-black text-white uppercase tracking-widest mb-1">Aura AI Tech</div>
                                <h3 class="text-xl font-black text-white leading-tight mb-2">Analisis Kulit Instan</h3>
                                <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] w-[85%] rounded-full relative">
                                        <div class="absolute inset-0 bg-white/30 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dermatology Small Card (Bottom Right inside LG) -->
                        <div class="hidden lg:flex col-span-5 row-span-5 glass-card bg-white/70 backdrop-blur-xl border border-white/80 rounded-[2.5rem] p-5 shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] items-center justify-center gap-3 float-slow hover:bg-white/90 transition-colors cursor-pointer group">
                            <div class="w-12 h-12 bg-[var(--tx-tertiary-light)] text-[var(--tx-tertiary)] rounded-full flex items-center justify-center text-xl group-hover:scale-110 transition-transform">🎓</div>
                            <div>
                                <h3 class="text-sm font-black text-[var(--tx-text-dark)]">Skin School</h3>
                                <p class="text-[9px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest mt-0.5">Edukasi Ahli</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 🚀 SECTION 2: AURA AI SHOWCASE -->
    <section id="aura-ai" class="py-24 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-[3rem] p-8 md:p-16 border border-white shadow-[0_20px_60px_-15px_rgba(244,114,182,0.15)] bg-white/60 relative overflow-hidden flex flex-col lg:flex-row items-center gap-12">
                
                <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-[var(--tx-primary-light)] to-transparent opacity-50 z-0"></div>

                <div class="lg:w-1/2 relative z-10 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[var(--tx-primary)]/20 text-[var(--tx-primary)] text-[10px] font-black uppercase tracking-widest mb-6 shadow-sm">
                        <span class="animate-pulse w-2 h-2 rounded-full bg-[var(--tx-primary)]"></span> Inovasi Terkini
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-[var(--tx-text-dark)] leading-tight mb-6">
                        Kenali Kulitmu dengan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-purple-600 drop-shadow-sm">Aura AI Scan</span>
                    </h2>
                    <p class="text-lg font-bold text-[var(--tx-text-muted)] mb-8 leading-relaxed">
                        Tidak perlu bingung memilih skincare. Aura AI akan menganalisis kondisi wajahmu, mendeteksi masalah kulit, dan memberikan rekomendasi produk yang 100% cocok secara instan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="btn-gradient font-black text-xs uppercase tracking-widest px-8 py-4 rounded-2xl shadow-[0_10px_20px_rgba(244,114,182,0.3)] hover:scale-105 transition-transform flex items-center justify-center gap-2">
                            <span>📸</span> Coba Scan Sekarang
                        </a>
                    </div>
                </div>

                <div class="lg:w-1/2 relative z-10 flex justify-center w-full">
                    <!-- Aura AI Card Replica -->
                    <div class="glass-card w-full max-w-sm p-8 rounded-[3rem] flex flex-col items-center justify-center text-center border border-white shadow-2xl relative overflow-hidden bg-white/90 backdrop-blur-2xl group hover:-translate-y-2 transition-all duration-500">
                        <div class="absolute inset-0 opacity-20 pointer-events-none transition-opacity group-hover:opacity-30" style="background: radial-gradient(circle at 50% 30%, var(--tx-secondary) 0%, transparent 70%);"></div>
                        <div class="relative z-10 aura-core mb-6">
                            <svg width="80" height="92" viewBox="0 0 140 160" fill="none">
                                <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6"/>
                                <circle cx="55" cy="20" r="4" fill="#9B8EC4"/>
                                <circle cx="70" cy="32" r="4" fill="#F472B6"/>
                                <circle cx="85" cy="20" r="4" fill="#9B8EC4"/>
                                <rect x="22" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                                <rect x="106" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                                <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                                <ellipse cx="55" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                <ellipse cx="85" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                <ellipse cx="55" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                <ellipse cx="85" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                <circle cx="57" cy="70" r="2" fill="white"/>
                                <circle cx="87" cy="70" r="2" fill="white"/>
                                <ellipse cx="42" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                                <ellipse cx="98" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                                <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#dashAura)"/>
                                <defs><linearGradient id="dashAura" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                            </svg>
                        </div>
                        <h4 class="relative z-10 text-2xl font-black text-[var(--tx-text-dark)] mb-2">Konsultasi AI Aura</h4>
                        <p class="relative z-10 text-[9px] text-[var(--tx-primary)] bg-[var(--tx-primary-light)] px-3 py-1.5 rounded-full font-black mb-8 uppercase tracking-widest border border-[var(--tx-primary)]/20 shadow-inner">Chat · Face Scan · Rekomendasi</p>
                        
                        <div class="relative z-10 w-full bg-white/60 border border-gray-100 rounded-[1.5rem] p-5 text-left shadow-sm mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-xs shadow-inner">🤖</span>
                                <div class="bg-gray-100 text-[var(--tx-text-dark)] text-[11px] font-bold py-2.5 px-4 rounded-tr-xl rounded-br-xl rounded-bl-xl border border-gray-200">Kondisimu: Oily & Acne-prone.</div>
                            </div>
                            <div class="flex items-center gap-3 justify-end">
                                <div class="bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white text-[11px] font-bold py-2.5 px-4 rounded-tl-xl rounded-bl-xl rounded-br-xl shadow-md shadow-[var(--tx-primary)]/20">Rekomendasi serum?</div>
                                <span class="w-8 h-8 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-xs">👤</span>
                            </div>
                        </div>

                        <div class="relative z-10 flex gap-3 w-full">
                            <div class="flex-1 btn-gradient py-4 rounded-2xl text-[10px] uppercase tracking-widest font-black flex justify-center items-center gap-2 cursor-pointer shadow-lg hover:scale-105 transition-transform">💬 Chat AI</div>
                            <div class="flex-1 bg-white border border-gray-200 text-[var(--tx-primary)] font-black text-[10px] uppercase tracking-widest py-4 rounded-2xl cursor-pointer shadow-sm text-center flex items-center justify-center gap-2 hover:bg-gray-50 transition-colors">📸 Scan Wajah</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 🌈 SECTION 3: TIGA PILAR -->
    <section id="fitur" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-[var(--tx-text-dark)] mb-6">Pilar Ekosistem Kami</h2>
                <p class="text-lg font-bold text-[var(--tx-text-muted)] max-w-2xl mx-auto">Kami mengintegrasikan kebutuhan kecantikan dan keuangan dalam satu platform yang terpusat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                <!-- Harvestly -->
                <div class="glass-card p-10 rounded-[3rem] border border-white/80 group hover:-translate-y-3 transition-transform duration-500 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/60 hover:bg-white/90">
                    <div class="w-20 h-20 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white rounded-[1.5rem] flex items-center justify-center text-4xl mb-8 shadow-lg shadow-[var(--tx-primary)]/30 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        💳
                    </div>
                    <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-4">Harvestly</h3>
                    <p class="text-[var(--tx-text-muted)] font-bold text-sm leading-relaxed">
                        Dompet digital cerdasmu. Bayar belanjaan tanpa ribet, lacak pengeluaran, dan kelola keuangan harian dengan antarmuka yang sangat mulus.
                    </p>
                </div>

                <!-- Naturea -->
                <div class="glass-card p-10 rounded-[3rem] border border-white/80 group hover:-translate-y-3 transition-transform duration-500 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/60 hover:bg-white/90">
                    <div class="w-20 h-20 bg-gradient-to-br from-[var(--tx-secondary)] to-amber-400 text-white rounded-[1.5rem] flex items-center justify-center text-4xl mb-8 shadow-lg shadow-[var(--tx-secondary)]/30 group-hover:scale-110 group-hover:-rotate-3 transition-transform">
                        🧴
                    </div>
                    <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-4">Naturea</h3>
                    <p class="text-[var(--tx-text-muted)] font-bold text-sm leading-relaxed">
                        Toko skincare natural. Temukan produk lokal Indonesia terbaik untuk kulit sehatmu yang telah dikurasi ketat oleh pakar kecantikan.
                    </p>
                </div>

                <!-- Karebla -->
                <div class="glass-card p-10 rounded-[3rem] border border-white/80 group hover:-translate-y-3 transition-transform duration-500 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/60 hover:bg-white/90">
                    <div class="w-20 h-20 bg-gradient-to-br from-[var(--tx-quaternary)] to-green-400 text-white rounded-[1.5rem] flex items-center justify-center text-4xl mb-8 shadow-lg shadow-[var(--tx-quaternary)]/30 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        🌱
                    </div>
                    <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-4">Karebla</h3>
                    <p class="text-[var(--tx-text-muted)] font-bold text-sm leading-relaxed">
                        Program peduli lingkungan! Tukar kemasan kosong kosmetikmu menjadi poin loyalty dan dapatkan reward eksklusif serta diskon belanja.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🌟 FOOTER (Card Style) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 relative z-10 mt-10">
        <footer class="glass-card bg-white/80 backdrop-blur-2xl border border-white rounded-[3rem] p-10 md:p-14 shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08)]">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12 border-b border-gray-200/50 pb-12">
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 mb-6 group">
                        <img src="{{ asset('images/logo trinexa.jpeg') }}" alt="Trinexa Logo" class="w-12 h-12 object-cover rounded-[14px] shadow-sm group-hover:scale-105 transition-transform">
                        <h2 class="text-3xl font-black text-[var(--tx-text-dark)] tracking-wider">TRINEXA</h2>
                    </a>
                    <p class="text-[var(--tx-text-muted)] font-bold mb-6 max-w-sm text-sm leading-relaxed">
                        Membangun ekosistem gaya hidup masa depan yang cerdas, inklusif, dan memberdayakan.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-black text-[var(--tx-text-dark)] mb-6 uppercase tracking-widest text-xs">Produk</h3>
                    <ul class="space-y-4 font-bold text-sm text-[var(--tx-text-muted)]">
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Harvestly Wallet</a></li>
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Naturea Shop</a></li>
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Aura AI Scan</a></li>
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Skin School</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-black text-[var(--tx-text-dark)] mb-6 uppercase tracking-widest text-xs">Perusahaan</h3>
                    <ul class="space-y-4 font-bold text-sm text-[var(--tx-text-muted)]">
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Privasi</a></li>
                        <li><a href="#" class="hover:text-[var(--tx-primary)] transition-colors">Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs font-black uppercase tracking-widest text-gray-400">
                    &copy; {{ date('Y') }} Trinexa Ecosystem. All rights reserved.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-[var(--tx-primary)] hover:text-white transition-all shadow-sm">IG</a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-[var(--tx-primary)] hover:text-white transition-all shadow-sm">TW</a>
                </div>
            </div>
        </footer>
    </div>

</body>
</html>
