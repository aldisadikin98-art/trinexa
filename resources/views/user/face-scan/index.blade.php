<x-app-layout>
    <x-slot name="title">Face AI Scan | Trinexa Dermatology</x-slot>

    <div class="py-12 min-h-screen relative z-10">

        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-pink)]/60 to-[var(--tx-secondary-light)] rounded-full blur-[100px] opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-[100px] opacity-50 pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Hero Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 glass-card bg-white/50 border border-white/70 px-5 py-2.5 rounded-full text-[var(--tx-primary)] font-black text-[10px] uppercase tracking-widest mb-6 shadow-sm">
                    <span class="text-lg">🤖</span> Powered by AI · Trinexa Dermatology
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-[var(--tx-text-dark)] mb-4 leading-tight">
                    Face AI <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]">Scan</span>
                </h1>
                <p class="text-[var(--tx-text-muted)] font-medium text-lg max-w-xl mx-auto leading-relaxed">
                    Upload foto wajahmu dan biarkan AI menganalisis kondisi kulit secara instan. Dapatkan rekomendasi perawatan yang tepat!
                </p>
            </div>

            <!-- How It Works -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
                @foreach([['📷', 'Upload Foto', 'Pilih foto wajah yang jelas & terang'], ['🤖', 'AI Analisis', 'Sistem mendeteksi kondisi kulit dalam detik'], ['✨', 'Hasil & Tips', 'Dapatkan rekomendasi perawatan personal']] as [$icon, $title, $desc])
                <div class="glass-card bg-white/40 rounded-[2rem] border border-white/60 p-6 text-center shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg shadow-[var(--tx-primary)]/20">{{ $icon }}</div>
                    <h3 class="font-black text-[var(--tx-text-dark)] text-sm mb-1 uppercase tracking-widest">{{ $title }}</h3>
                    <p class="text-[var(--tx-text-muted)] text-xs font-medium">{{ $desc }}</p>
                </div>
                @endforeach
            </div>

            @if($errors->any())
                <div class="glass-card bg-red-50/80 border border-red-200 text-red-600 font-bold text-sm px-6 py-4 rounded-2xl mb-8 shadow-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Upload Form -->
            <div class="glass-card bg-white/50 rounded-[3rem] border border-white/70 p-10 shadow-xl backdrop-blur-xl relative overflow-hidden"
                 x-data="{ preview: null, dragging: false, fileName: '' }">
                <div class="absolute -right-20 -top-20 w-60 h-60 bg-[var(--tx-secondary-light)] rounded-full blur-3xl opacity-40 pointer-events-none"></div>

                <form action="{{ route('user.face-scan.analyze') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
                    @csrf

                    <!-- Dropzone -->
                    <div class="mb-8"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="
                            dragging = false;
                            const file = $event.dataTransfer.files[0];
                            if (file) {
                                fileName = file.name;
                                const reader = new FileReader();
                                reader.onload = e => preview = e.target.result;
                                reader.readAsDataURL(file);
                                // Put file into input
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                $refs.fileInput.files = dt.files;
                            }
                         ">
                        <label for="face_image_input" class="block cursor-pointer">
                            <div :class="dragging ? 'border-[var(--tx-primary)] bg-[var(--tx-primary-light)]/30 scale-[1.02]' : 'border-white/60 hover:border-[var(--tx-primary)]/50 hover:bg-white/60'"
                                 class="border-[3px] border-dashed rounded-[2rem] p-10 text-center transition-all duration-300 bg-white/30 relative overflow-hidden">

                                <!-- Preview image -->
                                <template x-if="preview">
                                    <div class="relative">
                                        <img :src="preview" class="max-h-72 mx-auto rounded-2xl object-cover shadow-lg border-4 border-white">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity bg-black/20 rounded-2xl">
                                            <p class="text-white font-black text-sm uppercase tracking-widest bg-black/40 px-4 py-2 rounded-full">Ganti Foto</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Placeholder -->
                                <template x-if="!preview">
                                    <div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-4xl mx-auto mb-5 shadow-lg shadow-[var(--tx-primary)]/20">📷</div>
                                        <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-2">Seret & Lepas Foto di Sini</h3>
                                        <p class="text-[var(--tx-text-muted)] text-sm font-medium mb-6">atau klik untuk memilih file</p>
                                        <div class="inline-flex items-center gap-2 bg-white/60 border border-white/80 text-[var(--tx-text-muted)] text-xs font-black px-5 py-2.5 rounded-full shadow-sm uppercase tracking-widest">
                                            📁 Pilih Foto
                                        </div>
                                    </div>
                                </template>

                                <!-- File name -->
                                <p x-show="fileName" x-text="'📎 ' + fileName" class="mt-4 text-[10px] font-black text-[var(--tx-primary)] uppercase tracking-widest bg-[var(--tx-primary-light)] px-3 py-1.5 rounded-full inline-block"></p>
                            </div>
                        </label>
                        <input type="file" id="face_image_input" name="face_image" accept="image/*" required
                               x-ref="fileInput" class="hidden"
                               @change="
                                   const file = $event.target.files[0];
                                   if (file) {
                                       fileName = file.name;
                                       const reader = new FileReader();
                                       reader.onload = e => preview = e.target.result;
                                       reader.readAsDataURL(file);
                                   }
                               ">
                    </div>

                    <!-- Tips -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8 text-xs font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">
                        @foreach(['☀️ Foto dalam pencahayaan terang', '😐 Wajah menghadap ke depan', '🔍 JPG, PNG, max 5MB'] as $tip)
                        <div class="flex items-center gap-2 bg-white/50 border border-white/60 rounded-xl px-3 py-2.5 shadow-sm">
                            {{ $tip }}
                        </div>
                        @endforeach
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            :disabled="!preview"
                            :class="preview ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] hover:-translate-y-1 shadow-lg shadow-[var(--tx-primary)]/30 text-white cursor-pointer' : 'bg-gray-100/50 border border-white/60 text-gray-400 cursor-not-allowed'"
                            class="w-full font-black py-5 rounded-[1.5rem] transition-all text-base uppercase tracking-widest relative overflow-hidden group">
                        <div x-show="preview" class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative z-10" x-show="!preview">Upload Foto Dulu 🔒</span>
                        <span class="relative z-10 flex items-center justify-center gap-2" x-show="preview">
                            <span>🔬</span> Analisis Wajahku Sekarang
                        </span>
                    </button>
                </form>
            </div>

            <!-- Disclaimer -->
            <div class="text-center mt-8 glass-card bg-white/30 border border-white/50 rounded-2xl px-6 py-4 shadow-sm">
                <p class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">🔒 Foto diproses secara aman · Tidak disimpan permanen · Hanya untuk tujuan analisis kulit</p>
            </div>

        </div>
    </div>
</x-app-layout>
