<x-app-layout>
    <x-slot name="title">Profil Saya | Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
            <div class="mb-6 bg-white/60 backdrop-blur-md border border-white/50 text-[var(--tx-quaternary)] font-black text-sm px-6 py-4 rounded-[16px] shadow-lg flex items-center gap-3">
                <span class="text-xl">🌟</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- HERO PROFILE CARD --}}
        <div class="glass-card relative overflow-hidden mb-8 shadow-xl border border-white/60 bg-gradient-to-br from-white/40 to-white/10">
            <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at 80% 20%, var(--tx-secondary) 0%, transparent 60%);"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-center md:items-start gap-8">

                {{-- Avatar Upload --}}
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf @method('PATCH')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <div class="relative group shrink-0">
                        <div class="w-28 h-28 rounded-[24px] overflow-hidden border-4 border-white/80 shadow-xl bg-white/50 backdrop-blur-sm">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover" id="avatarPreview">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl font-black text-[var(--tx-primary)]" id="avatarInitial">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <img src="" class="w-full h-full object-cover hidden" id="avatarPreview">
                            @endif
                        </div>
                        <label for="avatarInput" class="absolute inset-0 rounded-[24px] bg-black/40 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                            <span class="text-white text-2xl drop-shadow-md">📷</span>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this); document.getElementById('avatarForm').submit();">
                    </div>
                </form>

                {{-- Info Utama --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-black text-[var(--tx-text-dark)] mb-1 drop-shadow-sm">{{ $user->name }}</h1>
                    <p class="text-[var(--tx-text-muted)] text-sm mb-4 font-bold">{{ $user->email }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2.5">
                        @if($user->phone)
                            <span class="bg-white/50 backdrop-blur-sm text-[var(--tx-text-dark)] text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-full border border-white">📞 {{ $user->phone }}</span>
                        @endif
                        @if($user->birth_date)
                            <span class="bg-white/50 backdrop-blur-sm text-[var(--tx-text-dark)] text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-full border border-white">🎂 {{ \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d M Y') }}</span>
                        @endif
                        <span class="bg-[var(--tx-primary)] text-white text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-full shadow-sm">
                            {{ $user->role === 'admin' ? '👑 Admin' : '✨ Member' }}
                        </span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex md:flex-col gap-4 md:gap-3 shrink-0 bg-white/30 backdrop-blur-md border border-white p-4 rounded-[20px] shadow-sm">
                    <div class="text-center">
                        <div class="text-xl font-black text-[var(--tx-primary)]">{{ $txCount }}</div>
                        <div class="text-[var(--tx-text-muted)] text-[9px] uppercase tracking-widest font-black">Pesanan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-black text-[var(--tx-tertiary)]">{{ $reviewCount }}</div>
                        <div class="text-[var(--tx-text-muted)] text-[9px] uppercase tracking-widest font-black">Ulasan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-black text-[var(--tx-quaternary)]">{{ $wallet->balance >= 1000000 ? number_format($wallet->balance/1000000, 1).'jt' : number_format($wallet->balance/1000, 0).'rb' }}</div>
                        <div class="text-[var(--tx-text-muted)] text-[9px] uppercase tracking-widest font-black">Saldo</div>
                    </div>
                </div>
            </div>

            {{-- Kelengkapan Profil --}}
            @php
                $filled = collect([$user->name, $user->email, $user->phone, $user->address, $user->gender, $user->birth_date, $user->avatar])->filter()->count();
                $pct = round(($filled / 7) * 100);
            @endphp
            <div class="relative border-t border-white/50 px-6 md:px-10 py-5 flex items-center gap-4 bg-white/20">
                <div class="flex-1 bg-white/40 rounded-full h-3 overflow-hidden border border-white/60 shadow-inner">
                    <div class="h-3 rounded-full bg-gradient-to-r from-[var(--tx-secondary)] to-[var(--tx-primary)] transition-all duration-1000" style="width: {{ $pct }}%"></div>
                </div>
                <span class="text-[var(--tx-text-dark)] text-[10px] uppercase tracking-widest font-black whitespace-nowrap bg-white/50 px-3 py-1 rounded-full border border-white shadow-sm">{{ $pct }}% Lengkap</span>
            </div>
        </div>

        {{-- MENU SHORTCUT (Shopee-style) --}}
        <div class="grid grid-cols-4 gap-4 mb-8">
            @php
                $shortcuts = [
                    ['icon' => '📦', 'label' => 'Pesanan', 'url' => route('transaction.index'), 'color' => 'var(--tx-primary)'],
                    ['icon' => '💳', 'label' => 'Dompet', 'url' => route('user.wallet.show'), 'color' => 'var(--tx-secondary)'],
                    ['icon' => '🛍️', 'label' => 'Keranjang', 'url' => route('cart.index'), 'color' => 'var(--tx-tertiary)'],
                    ['icon' => '🎖️', 'label' => 'Loyalty', 'url' => route('user.loyalty.index'), 'color' => 'var(--tx-quaternary)'],
                ];
            @endphp
            @foreach($shortcuts as $s)
                <a href="{{ $s['url'] }}" class="glass-card flex flex-col items-center gap-2 p-5 text-center border border-white/50 hover:-translate-y-1 hover:shadow-lg transition-all group">
                    <div class="text-3xl mb-1 group-hover:scale-110 transition-transform drop-shadow-sm">{{ $s['icon'] }}</div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-[var(--tx-text-dark)] group-hover:text-[{{ $s['color'] }}] transition-colors">{{ $s['label'] }}</div>
                </a>
            @endforeach
        </div>

        {{-- TWO COLUMN LAYOUT — satu x-data untuk share state tab --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="{ tab: 'info' }">

            {{-- KIRI: Navigasi Seksi --}}
            <div class="md:col-span-1 space-y-3">
                @php
                    $tabs = [
                        ['key' => 'info',     'icon' => '👤', 'label' => 'Informasi Diri'],
                        ['key' => 'address',  'icon' => '📍', 'label' => 'Alamat Pengiriman'],
                        ['key' => 'security', 'icon' => '🔐', 'label' => 'Keamanan Akun'],
                        ['key' => 'delete',   'icon' => '🗑️', 'label' => 'Hapus Akun'],
                    ];
                @endphp
                @foreach($tabs as $t)
                    <button @click="tab = '{{ $t['key'] }}'"
                            :class="tab === '{{ $t['key'] }}' ? 'bg-white/60 text-[var(--tx-primary)] border-white shadow-md' : 'bg-white/30 text-[var(--tx-text-dark)] border-white/40 hover:bg-white/50'"
                            class="w-full flex items-center gap-3 px-5 py-4 rounded-[16px] border backdrop-blur-sm text-left font-black text-xs uppercase tracking-widest transition-all">
                        <span class="text-xl">{{ $t['icon'] }}</span>
                        {{ $t['label'] }}
                        <span class="ml-auto opacity-50" x-show="tab === '{{ $t['key'] }}'">●</span>
                    </button>
                @endforeach

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full pt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-4 rounded-[16px] border border-red-200 bg-red-50 text-red-500 hover:bg-red-100 font-black text-xs uppercase tracking-widest transition-all shadow-sm">
                        <span class="text-xl">🚪</span> Keluar Akun
                    </button>
                </form>
            </div>

            {{-- KANAN: Konten Tab --}}
            <div class="md:col-span-2">

                {{-- TAB: Informasi Diri --}}
                <div x-show="tab === 'info'" class="glass-card border border-white/50 p-8">
                    <h2 class="font-black text-[var(--tx-text-dark)] text-xl mb-6 flex items-center gap-3">
                        <span class="bg-white/60 w-10 h-10 rounded-[12px] flex items-center justify-center border border-white shadow-sm">👤</span>
                        Informasi Diri
                    </h2>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Nama Lengkap *</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm font-bold text-[var(--tx-text-dark)] transition-all backdrop-blur-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Email *</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm font-bold text-[var(--tx-text-dark)] transition-all backdrop-blur-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">No. HP</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08XXXXXXXXXX" class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm font-bold text-[var(--tx-text-dark)] transition-all backdrop-blur-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Tanggal Lahir</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm font-bold text-[var(--tx-text-dark)] transition-all backdrop-blur-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-3 uppercase tracking-widest">Jenis Kelamin</label>
                            <div class="flex gap-3">
                                @foreach(['male' => '👨 Laki-laki', 'female' => '👩 Perempuan', 'other' => '🧑 Lainnya'] as $val => $label)
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="gender" value="{{ $val }}" class="sr-only peer" {{ old('gender', $user->gender) === $val ? 'checked' : '' }}>
                                        <div class="text-center py-3 px-2 border border-white/60 bg-white/30 rounded-[12px] text-xs font-black transition-all peer-checked:border-[var(--tx-primary)] peer-checked:bg-[var(--tx-primary-light)] peer-checked:text-[var(--tx-primary)] text-[var(--tx-text-dark)] hover:bg-white/50 shadow-sm backdrop-blur-sm">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="w-full btn-gradient py-3.5 mt-2">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                {{-- TAB: Alamat --}}
                <div x-show="tab === 'address'" style="display:none" class="glass-card border border-white/50 p-8">
                    <h2 class="font-black text-[var(--tx-text-dark)] text-xl mb-2 flex items-center gap-3">
                        <span class="bg-white/60 w-10 h-10 rounded-[12px] flex items-center justify-center border border-white shadow-sm">📍</span>
                        Alamat Pengiriman
                    </h2>
                    <p class="text-xs font-bold text-[var(--tx-text-muted)] mb-6 ml-14">Alamat ini otomatis terisi saat checkout.</p>

                    @if($user->address)
                        <div class="bg-white/60 border border-white rounded-[16px] p-5 mb-6 flex items-start gap-4 shadow-sm backdrop-blur-sm">
                            <span class="text-2xl mt-0.5 shrink-0 text-[var(--tx-primary)]">🏠</span>
                            <div>
                                <div class="font-black text-[var(--tx-text-dark)] text-sm mb-1 uppercase tracking-widest">{{ $user->name }}</div>
                                <div class="text-sm font-bold text-gray-600 leading-relaxed">{{ $user->address }}</div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                        @csrf @method('PATCH')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Alamat Lengkap</label>
                            <textarea name="address" id="addressTextarea" rows="4" placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos..."
                                class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm font-bold text-[var(--tx-text-dark)] transition-all resize-none backdrop-blur-sm">{{ old('address', $user->address) }}</textarea>
                        </div>

                        {{-- Peta OpenStreetMap --}}
                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Pin Lokasi di Peta</label>
                            <div class="bg-white/50 p-1.5 rounded-[16px] border border-white/60 backdrop-blur-sm">
                                <div id="map" class="w-full h-56 rounded-[12px] overflow-hidden bg-gray-100 z-0 relative"></div>
                            </div>
                            <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-2">📌 Klik peta → alamat otomatis terisi di kotak di atas</p>
                        </div>

                        <button type="submit" class="w-full btn-gradient py-3.5 mt-2">
                            Simpan Alamat
                        </button>
                    </form>
                </div>

                {{-- TAB: Keamanan --}}
                <div x-show="tab === 'security'" style="display:none" class="glass-card border border-white/50 p-8">
                    <h2 class="font-black text-[var(--tx-text-dark)] text-xl mb-6 flex items-center gap-3">
                        <span class="bg-white/60 w-10 h-10 rounded-[12px] flex items-center justify-center border border-white shadow-sm">🔐</span>
                        Keamanan Akun
                    </h2>
                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Password Saat Ini</label>
                            <input type="password" name="current_password" required class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm transition-all backdrop-blur-sm">
                            @error('current_password') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Password Baru</label>
                            <input type="password" name="password" required minlength="8" class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm transition-all backdrop-blur-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] mb-2 uppercase tracking-widest">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required minlength="8" class="w-full bg-white/50 border border-white/60 focus:border-[var(--tx-secondary)] focus:ring-[var(--tx-secondary-light)] rounded-[12px] px-4 py-3 text-sm transition-all backdrop-blur-sm">
                        </div>
                        <button type="submit" class="w-full btn-gradient py-3.5 mt-2">
                            Ubah Password
                        </button>
                    </form>

                    <div class="mt-8 pt-8 border-t border-white/50">
                        <div class="flex items-center gap-4 p-4 bg-[var(--tx-quaternary-light)] rounded-[16px] border border-white shadow-sm">
                            <div class="w-10 h-10 rounded-[12px] bg-white flex items-center justify-center text-[var(--tx-quaternary)] text-xl shrink-0">
                                ✓
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-[var(--tx-quaternary)] uppercase tracking-widest mb-1">Akun Terverifikasi</div>
                                <div class="text-xs font-bold text-gray-600">Email Anda sudah terverifikasi dengan aman.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Hapus Akun --}}
                <div x-show="tab === 'delete'" style="display:none" class="glass-card border border-red-200 p-8 bg-red-50/30">
                    <h2 class="font-black text-red-600 text-xl mb-3 flex items-center gap-3">
                        <span class="bg-red-100 w-10 h-10 rounded-[12px] flex items-center justify-center border border-red-200 shadow-sm text-xl">🗑️</span>
                        Hapus Akun
                    </h2>
                    <p class="text-sm font-bold text-gray-600 mb-6 ml-14 leading-relaxed">Tindakan ini <strong class="text-red-500">tidak dapat dibatalkan</strong>. Seluruh data, saldo Harvestly, pesanan, dan riwayat Anda akan dihapus permanen dari sistem.</p>
                    
                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun? Ini tidak bisa dibatalkan!')" class="ml-14">
                        @csrf @method('DELETE')
                        <div class="mb-5">
                            <label class="block text-[10px] font-black text-red-500 mb-2 uppercase tracking-widest">Konfirmasi dengan Password</label>
                            <input type="password" name="password" required class="w-full bg-white border border-red-200 focus:border-red-400 focus:ring-red-200 rounded-[12px] px-4 py-3 text-sm transition-all shadow-inner">
                        </div>
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-black uppercase tracking-widest text-xs py-3.5 rounded-[12px] transition-all shadow-md hover:shadow-red-500/30 border border-red-600">
                            Hapus Akun Saya Permanen
                        </button>
                    </form>
                </div>

            </div>{{-- end kanan --}}
        </div>{{-- end grid --}}
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Avatar Preview
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('avatarPreview');
                    const initial = document.getElementById('avatarInitial');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (initial) initial.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // ── Leaflet Map ──────────────────────────────────────────────
        let leafletMap = null;

        function initMap() {
            if (leafletMap) {
                // Already initialized — just invalidate size (tab visibility fix)
                setTimeout(() => leafletMap.invalidateSize(), 100);
                return;
            }

            leafletMap = L.map('map').setView([-6.2088, 106.8456], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(leafletMap);

            let marker = null;
            const addressField = document.getElementById('addressTextarea');

            leafletMap.on('click', async (e) => {
                const { lat, lng } = e.latlng;
                if (marker) marker.remove();
                marker = L.marker([lat, lng]).addTo(leafletMap);

                // Geocode via Nominatim
                try {
                    const res  = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`);
                    const data = await res.json();
                    if (data.display_name && addressField) addressField.value = data.display_name;
                } catch {
                    if (addressField) addressField.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                }
            });

            // Geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => leafletMap.setView([pos.coords.latitude, pos.coords.longitude], 15),
                    () => {} // ignore denial
                );
            }
        }

        // Init map when address tab becomes visible
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                // We watch for the map div to become visible via MutationObserver
            });
        });

        // MutationObserver — detect when map div becomes visible
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new MutationObserver(() => {
                const mapEl = document.getElementById('map');
                if (mapEl && mapEl.offsetParent !== null) {
                    initMap();
                }
            });

            const target = document.getElementById('map');
            if (target) {
                observer.observe(target.parentElement, { attributes: true, subtree: true });
            }

            // Also listen to tab button clicks
            document.querySelectorAll('button[\\@click]').forEach(btn => {
                btn.addEventListener('click', () => {
                    setTimeout(() => {
                        const mapEl = document.getElementById('map');
                        if (mapEl && mapEl.offsetParent !== null) initMap();
                    }, 50);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
