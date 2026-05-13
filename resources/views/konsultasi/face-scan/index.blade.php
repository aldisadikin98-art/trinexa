<x-app-layout>
    <x-slot name="title">Face Scan AI | Truevera Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

            {{-- ── CAMERA / UPLOAD CARD ────────────────────────────── --}}
            <div class="glass-card border border-white/50 p-8" x-data="faceScan()">

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-[18px] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-2xl shadow-lg border border-white/50">📸</div>
                    <div>
                        <h2 class="font-black text-[var(--tx-text-dark)] text-xl">Face Scan AI</h2>
                        <p class="text-xs font-bold text-[var(--tx-text-muted)] uppercase tracking-widest mt-0.5">Analisis kulit wajah berbasis AI Vision</p>
                    </div>
                </div>

                {{-- Mode Tabs --}}
                <div class="flex gap-2 mb-5">
                    <button @click="mode = 'camera'" :class="mode === 'camera' ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-md' : 'bg-white/40 text-[var(--tx-text-muted)] border border-white/60'"
                        class="flex-1 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                        📷 Buka Kamera
                    </button>
                    <button @click="mode = 'upload'; stopCamera()" :class="mode === 'upload' ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-md' : 'bg-white/40 text-[var(--tx-text-muted)] border border-white/60'"
                        class="flex-1 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                        🖼️ Upload Foto
                    </button>
                </div>

                {{-- ── CAMERA MODE ── --}}
                <div x-show="mode === 'camera'" x-cloak>
                    <div class="relative bg-black rounded-[20px] overflow-hidden aspect-[4/3] mb-4 border-2 border-white/40 shadow-inner">

                        {{-- Live camera feed --}}
                        <video id="cameraFeed" autoplay playsinline muted
                            class="w-full h-full object-cover"
                            style="transform: scaleX(-1) !important;"
                            x-show="cameraActive && !capturedPhoto"></video>

                        {{-- Captured photo preview --}}
                        <img id="capturedImg" x-show="capturedPhoto" :src="capturedPhoto"
                            class="w-full h-full object-cover"
                            style="transform: scaleX(-1) !important;">

                        {{-- Camera off placeholder --}}
                        <div x-show="!cameraActive && !capturedPhoto"
                            class="absolute inset-0 flex flex-col items-center justify-center gap-4 bg-gradient-to-br from-gray-900 to-gray-800">
                            <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center text-4xl border border-white/20">📷</div>
                            <p class="text-white/70 font-black text-sm uppercase tracking-widest">Kamera belum aktif</p>
                            <button @click="startCamera()"
                                class="btn-gradient px-6 py-2.5 text-sm gap-2">
                                🎥 Aktifkan Kamera
                            </button>
                        </div>

                        {{-- Scanning overlay --}}
                        <div x-show="scanning" class="absolute inset-0 bg-black/50 backdrop-blur-sm flex flex-col items-center justify-center z-10">
                            <div class="relative w-full h-0.5 bg-[var(--tx-secondary)] scan-active absolute top-0 left-0 shadow-lg shadow-[var(--tx-secondary)]"></div>
                            <div class="truevera-think mb-4">
                                <svg width="60" height="68" viewBox="0 0 140 160" fill="none">
                                    <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6"/>
                                    <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                                    <ellipse cx="55" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                    <ellipse cx="85" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                    <ellipse cx="55" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                    <ellipse cx="85" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                    <circle cx="57" cy="70" r="2" fill="white"/>
                                    <circle cx="87" cy="70" r="2" fill="white"/>
                                    <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                    <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bGScan2)"/>
                                    <defs><linearGradient id="bGScan2" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                                </svg>
                            </div>
                            <p class="text-white font-black text-sm uppercase tracking-widest">Menganalisis kulit wajah...</p>
                            <p class="text-white/60 text-[10px] font-bold mt-1 uppercase tracking-widest">Processing Image</p>
                        </div>

                        {{-- Face guide overlay --}}
                        <div x-show="cameraActive && !capturedPhoto && !scanning"
                            class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-20">
                            
                            {{-- Oval Frame --}}
                            <div class="w-44 h-56 border-4 rounded-[100px] transition-colors duration-300 relative flex items-center justify-center"
                                :class="faceDetected ? 'border-green-400' : 'border-red-500/80'"
                                style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.4);">
                                
                                {{-- Corner brackets for high-tech feel --}}
                                <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 rounded-tl-[100px]" :class="faceDetected ? 'border-green-400' : 'border-red-500'"></div>
                                <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 rounded-tr-[100px]" :class="faceDetected ? 'border-green-400' : 'border-red-500'"></div>
                                <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 rounded-bl-[100px]" :class="faceDetected ? 'border-green-400' : 'border-red-500'"></div>
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 rounded-br-[100px]" :class="faceDetected ? 'border-green-400' : 'border-red-500'"></div>
                            </div>

                            {{-- Status Text --}}
                            <div class="absolute bottom-6 flex flex-col items-center gap-1">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-white transition-colors duration-300 backdrop-blur-md border"
                                    :class="faceDetected ? 'bg-green-500/80 border-green-400' : 'bg-red-500/80 border-red-400'">
                                    <span x-text="faceDetected ? '✅ Wajah Terdeteksi' : '⚠️ Posisikan Wajah di Oval'"></span>
                                </span>
                            </div>
                        </div>

                        {{-- Live indicator --}}
                        <div x-show="cameraActive && !capturedPhoto" class="absolute top-3 left-3 flex items-center gap-1.5 bg-black/40 backdrop-blur-sm px-3 py-1 rounded-full border border-white/20">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="text-white text-[9px] font-black uppercase tracking-widest">LIVE</span>
                        </div>
                    </div>

                    {{-- Camera controls --}}
                    <div x-show="!capturedPhoto" class="flex gap-3">
                        <button x-show="cameraActive" @click="flipCamera()"
                            class="flex-1 py-3 bg-white/40 border border-white/60 text-[var(--tx-text-dark)] font-black text-xs uppercase tracking-widest rounded-[14px] hover:bg-white/60 transition-all">
                            🔄 Balik
                        </button>
                        <button x-show="cameraActive" @click="capturePhoto()"
                            class="flex-1 btn-gradient py-3 text-sm gap-2">
                            📸 Ambil Foto
                        </button>
                        <button x-show="!cameraActive" @click="startCamera()"
                            class="w-full btn-gradient py-3 text-sm gap-2">
                            🎥 Aktifkan Kamera
                        </button>
                    </div>

                    <div x-show="capturedPhoto" class="flex gap-3">
                        <button @click="retakePhoto()" class="flex-1 py-3.5 bg-white/50 border border-white/60 text-[var(--tx-text-muted)] font-black text-xs uppercase tracking-widest rounded-[14px] hover:bg-white/70 transition-all">
                            🔄 Ulangi
                        </button>
                        <button @click="analyzeCapture()" :disabled="scanning"
                            class="flex-1 btn-gradient py-3.5 text-sm disabled:opacity-50 disabled:cursor-not-allowed gap-2">
                            <span x-show="!scanning">🔍 Analisis</span>
                            <span x-show="scanning">⏳ Menganalisis...</span>
                        </button>
                    </div>
                </div>

                {{-- ── UPLOAD MODE ── --}}
                <div x-show="mode === 'upload'" x-cloak>
                    <label for="photoInput"
                        class="block w-full aspect-[4/3] rounded-[20px] border-2 border-dashed border-[var(--tx-secondary)]/40 bg-white/30 hover:bg-white/50 hover:border-[var(--tx-secondary)] transition-all cursor-pointer overflow-hidden group"
                        :class="{ 'border-solid border-[var(--tx-primary)]': uploadPreview }">

                        <img x-show="uploadPreview" :src="uploadPreview" class="w-full h-full object-cover" x-cloak>

                        {{-- Scanning overlay upload --}}
                        <div x-show="scanning" class="absolute inset-0 bg-black/40 backdrop-blur-sm flex flex-col items-center justify-center z-10 rounded-[20px]">
                            <div class="truevera-think mb-3">
                                <svg width="50" height="56" viewBox="0 0 140 160" fill="none">
                                    <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6"/>
                                    <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                                    <ellipse cx="55" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                    <ellipse cx="85" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                    <ellipse cx="55" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                    <ellipse cx="85" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                    <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                    <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bGUp)"/>
                                    <defs><linearGradient id="bGUp" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                                </svg>
                            </div>
                            <p class="text-white font-black text-sm">Menganalisis...</p>
                        </div>

                        <div x-show="!uploadPreview" class="w-full h-full flex flex-col items-center justify-center gap-4 p-6">
                            <div class="w-20 h-20 rounded-full bg-[var(--tx-secondary-light)] flex items-center justify-center text-4xl border-4 border-white shadow-inner group-hover:scale-110 transition-transform">🖼️</div>
                            <div class="text-center">
                                <p class="font-black text-[var(--tx-text-dark)] text-sm">Klik untuk upload foto</p>
                                <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-1 uppercase tracking-widest">JPG / PNG / MAX 5MB</p>
                            </div>
                        </div>
                    </label>
                    <input type="file" id="photoInput" accept="image/*" class="hidden" @change="onUpload($event)">

                    <div x-show="uploadPreview" class="flex gap-3 mt-4">
                        <button @click="uploadPreview = null" class="flex-1 py-3.5 bg-white/50 border border-white/60 text-[var(--tx-text-muted)] font-black text-xs uppercase tracking-widest rounded-[14px]">Ganti</button>
                        <button @click="analyzeUpload()" :disabled="scanning" class="flex-1 btn-gradient py-3.5 text-sm disabled:opacity-50">
                            <span x-show="!scanning">🔍 Analisis</span>
                            <span x-show="scanning">⏳ Menganalisis...</span>
                        </button>
                    </div>
                </div>

                {{-- Error --}}
                <div x-show="errorMsg" class="mt-4 bg-red-50/80 border border-red-200 rounded-[16px] p-4 text-red-600 font-bold text-sm flex gap-2">
                    <span>⚠️</span><span x-text="errorMsg"></span>
                </div>

                {{-- Hidden canvas for capture --}}
                <canvas id="captureCanvas" class="hidden"></canvas>
            </div>

            {{-- ── HOW IT WORKS ──────────────────────────────────────── --}}
            <div class="glass-card border border-white/50 p-8 flex flex-col justify-between">
                <div>
                    <h3 class="font-black text-[var(--tx-text-dark)] text-xl mb-6 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-[12px] bg-[var(--tx-primary-light)] flex items-center justify-center border border-white shadow-sm">🧬</span>
                        Cara Kerja Face Scan
                    </h3>
                    <div class="space-y-5">
                        @foreach([
                            ['icon'=>'📷','title'=>'Aktifkan Kamera / Upload','desc'=>'Buka kamera dan posisikan wajahmu di dalam oval, atau upload foto selfie yang jelas.'],
                            ['icon'=>'🤖','title'=>'AI Menganalisis Visual','desc'=>'Groq Vision AI membaca kondisi kulit: hidrasi, pori-pori, produksi minyak, elastisitas, dan hiperpigmentasi.'],
                            ['icon'=>'📊','title'=>'Laporan Lengkap','desc'=>'Terima skin score, rutinitas pagi & malam yang dipersonalisasi, bahan aktif yang cocok, dan rekomendasi produk Naturea.'],
                        ] as $s)
                        <div class="flex items-start gap-4 group">
                            <div class="w-11 h-11 rounded-[14px] shrink-0 flex items-center justify-center text-xl border border-white/60 shadow-sm bg-white/40 group-hover:scale-110 transition-transform">{{ $s['icon'] }}</div>
                            <div>
                                <p class="font-black text-[var(--tx-text-dark)] text-sm">{{ $s['title'] }}</p>
                                <p class="text-xs font-bold text-[var(--tx-text-muted)] mt-1 leading-relaxed">{{ $s['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Tips foto bagus --}}
                    <div class="mt-8 p-5 bg-white/40 border border-white/60 rounded-[16px]">
                        <p class="font-black text-[var(--tx-text-dark)] text-xs uppercase tracking-widest mb-3">✨ Tips Foto Terbaik</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['💡 Pencahayaan alami', '😊 Wajah menghadap kamera', '🧴 Wajah bersih tanpa makeup', '📏 Jarak 30–50cm dari kamera'] as $t)
                            <div class="flex items-center gap-2 text-[10px] font-bold text-[var(--tx-text-muted)]">{{ $t }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-amber-50/60 border border-amber-200 rounded-[16px] text-xs font-bold text-amber-700 flex items-start gap-3">
                    <span class="text-lg shrink-0">⚠️</span>
                    <span>Hasil analisis bersifat informatif dan tidak menggantikan konsultasi dokter kulit profesional.</span>
                </div>
            </div>
        </div>

        {{-- ── RIWAYAT SCAN ─────────────────────────────────────────── --}}
        @if($scans->count())
        <div class="glass-card border border-white/50 overflow-hidden">
            <div class="px-8 py-5 border-b border-white/50 bg-white/20 flex items-center justify-between">
                <h3 class="font-black text-[var(--tx-text-dark)] text-lg">📂 Riwayat Face Scan</h3>
                <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest bg-white/50 px-3 py-1.5 rounded-full border border-white/60">{{ $scans->total() }} scan</span>
            </div>
            <div class="divide-y divide-white/40">
                @foreach($scans as $scan)
                <div class="flex items-center gap-5 px-8 py-5 hover:bg-white/30 transition-colors group">
                    <div class="w-16 h-16 rounded-[16px] overflow-hidden border-2 border-white/80 shadow-md shrink-0">
                        <img src="{{ Storage::url($scan->photo_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://placehold.co/64'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="font-black text-[var(--tx-text-dark)] text-sm">{{ $scan->skin_type }}</span>
                            <span class="bg-[var(--tx-primary-light)] text-[var(--tx-primary)] text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-white shadow-sm">Skor {{ $scan->skin_score }}/100</span>
                        </div>
                        <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-1 uppercase tracking-widest">{{ $scan->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs font-bold text-[var(--tx-text-dark)] mt-1 line-clamp-1">{{ $scan->score_label }}</p>
                    </div>
                    <a href="{{ route('konsultasi.face-scan.show', $scan) }}" class="btn-gradient py-2 px-5 text-xs shrink-0">Lihat →</a>
                </div>
                @endforeach
            </div>
            @if($scans->hasPages())
            <div class="p-6 border-t border-white/40">{{ $scans->links() }}</div>
            @endif
        </div>
        @else
        <div class="glass-card border border-white/50 p-12 text-center">
            <div class="text-6xl mb-4 opacity-40 grayscale">📸</div>
            <p class="font-black text-[var(--tx-text-dark)] text-lg mb-2">Belum ada riwayat scan</p>
            <p class="text-sm font-bold text-[var(--tx-text-muted)]">Lakukan face scan pertamamu di atas!</p>
        </div>
        @endif
    </div>

    @push('scripts')
    <!-- Tracking.js for Face Detection -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/tracking-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/data/face-min.js"></script>

    <script>
    function faceScan() {
        return {
            mode: 'camera',
            cameraActive: false,
            facingMode: 'user',
            capturedPhoto: null,
            uploadPreview: null,
            uploadFile: null,
            scanning: false,
            errorMsg: '',
            stream: null,
            faceDetected: false,
            trackerTask: null,

            init() {
                // Auto-start camera on load
                this.$nextTick(() => this.startCamera());
            },

            async startCamera() {
                this.errorMsg = '';
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: this.facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
                    });
                    const video = document.getElementById('cameraFeed');
                    video.srcObject = this.stream;
                    this.cameraActive = true;
                    
                    // Wait for video to be ready then start tracking
                    video.onloadedmetadata = () => {
                        this.startFaceTracking(video);
                    };
                } catch (e) {
                    this.errorMsg = 'Tidak bisa mengakses kamera. Pastikan izin kamera sudah diberikan di browser.';
                }
            },

            startFaceTracking(video) {
                // Initialize Tracker with higher sensitivity
                const tracker = new tracking.ObjectTracker('face');
                tracker.setInitialScale(2.5); // Lower scale = more sensitive to smaller/farther faces
                tracker.setStepSize(1.5);    // Smaller step = more precise
                tracker.setEdgesDensity(0.05); // Lower density = more lenient

                this.trackerTask = tracking.track(video, tracker);

                tracker.on('track', (event) => {
                    if (event.data.length === 0) {
                        this.faceDetected = false;
                    } else {
                        // Wajah ditemukan!
                        this.faceDetected = true;
                    }
                });
            },

            stopCamera() {
                if (this.trackerTask) {
                    this.trackerTask.stop();
                    this.trackerTask = null;
                }
                this.faceDetected = false;
                
                if (this.stream) {
                    this.stream.getTracks().forEach(t => t.stop());
                    this.stream = null;
                }
                this.cameraActive = false;
            },

            async flipCamera() {
                this.facingMode = this.facingMode === 'user' ? 'environment' : 'user';
                this.stopCamera();
                await this.startCamera();
            },

            capturePhoto() {
                const video = document.getElementById('cameraFeed');
                const canvas = document.getElementById('captureCanvas');
                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                this.capturedPhoto = canvas.toDataURL('image/jpeg', 0.92);
                this.stopCamera();
            },

            retakePhoto() {
                this.capturedPhoto = null;
                this.startCamera();
            },

            async analyzeCapture() {
                if (!this.capturedPhoto || this.scanning) return;
                this.scanning = true;
                this.errorMsg = '';
                // Convert base64 dataURL → Blob → File
                const blob = await (await fetch(this.capturedPhoto)).blob();
                const file = new File([blob], 'face-scan.jpg', { type: 'image/jpeg' });
                await this.submitFile(file);
            },

            onUpload(event) {
                const f = event.target.files[0];
                if (!f) return;
                if (f.size > 5 * 1024 * 1024) { this.errorMsg = 'Ukuran foto maksimal 5MB.'; return; }
                this.uploadFile = f;
                this.errorMsg = '';
                const reader = new FileReader();
                reader.onload = e => { this.uploadPreview = e.target.result; };
                reader.readAsDataURL(f);
            },

            async analyzeUpload() {
                if (!this.uploadFile || this.scanning) return;
                this.scanning = true;
                this.errorMsg = '';
                await this.submitFile(this.uploadFile);
            },

            async submitFile(file) {
                const fd = new FormData();
                fd.append('photo', file);
                fd.append('_token', '{{ csrf_token() }}');
                try {
                    const res  = await fetch('{{ route("konsultasi.face-scan.analyze") }}', { method: 'POST', body: fd });
                    const data = await res.json();
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        this.errorMsg = data.message || 'Gagal menganalisis foto.';
                        this.scanning = false;
                    }
                } catch {
                    this.errorMsg = 'Koneksi bermasalah. Coba lagi.';
                    this.scanning = false;
                }
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
