<x-app-layout>
    <x-slot name="title">{{ isset($content) ? 'Edit Konten' : 'Tambah Konten' }} | Admin Trinexa</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ type: '{{ old('type', $content->type ?? 'article') }}' }">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.dermatology.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-gray-400 hover:text-[var(--tx-primary)] transition-colors">
                ←
            </a>
            <div>
                <h1 class="text-2xl font-black text-[var(--tx-text-dark)]">{{ isset($content) ? 'Edit Konten' : 'Tambah Konten Baru' }}</h1>
                <p class="text-sm font-bold text-[var(--tx-text-muted)] mt-1">Isi detail materi Dermatology di bawah ini.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl font-bold text-sm">
                Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.
            </div>
        @endif

        <form action="{{ isset($content) ? route('admin.dermatology.update', $content) : route('admin.dermatology.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($content)) @method('PUT') @endif

            <!-- Tipe & Jenis Kulit -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Tipe Konten <span class="text-red-500">*</span></label>
                    <select name="type" x-model="type" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                        <option value="article">📄 Artikel (Panjang)</option>
                        <option value="tip">💡 Tips (Singkat)</option>
                        <option value="video">🎥 Video (YouTube)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Jenis Kulit <span class="text-red-500">*</span></label>
                    <select name="skin_type" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                        <option value="all" {{ old('skin_type', $content->skin_type ?? '') == 'all' ? 'selected' : '' }}>Semua Jenis Kulit</option>
                        <option value="oily" {{ old('skin_type', $content->skin_type ?? '') == 'oily' ? 'selected' : '' }}>Oily (Berminyak)</option>
                        <option value="dry" {{ old('skin_type', $content->skin_type ?? '') == 'dry' ? 'selected' : '' }}>Dry (Kering)</option>
                        <option value="combination" {{ old('skin_type', $content->skin_type ?? '') == 'combination' ? 'selected' : '' }}>Combination</option>
                        <option value="sensitive" {{ old('skin_type', $content->skin_type ?? '') == 'sensitive' ? 'selected' : '' }}>Sensitive</option>
                        <option value="normal" {{ old('skin_type', $content->skin_type ?? '') == 'normal' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
            </div>

            <!-- Judul & Meta -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-6">
                <div>
                    <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Judul Konten <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $content->title ?? '') }}" placeholder="Contoh: 5 Kesalahan Skincare Pemula" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                    @error('title') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">XP Reward <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="xp_reward" value="{{ old('xp_reward', $content->xp_reward ?? 30) }}" min="0" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)] pr-12">
                            <span class="absolute right-4 top-2.5 text-amber-500 font-bold text-sm">XP</span>
                        </div>
                    </div>
                    
                    <div x-show="type !== 'video'">
                        <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Waktu Baca (Menit)</label>
                        <div class="relative">
                            <input type="number" name="read_time" value="{{ old('read_time', $content->read_time ?? 5) }}" min="1" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)] pr-12">
                            <span class="absolute right-4 top-2.5 text-gray-400 font-bold text-sm">Mnt</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">Thumbnail (Opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-[var(--tx-primary-light)] file:text-[var(--tx-primary)] hover:file:bg-[var(--tx-primary)] hover:file:text-white transition-all cursor-pointer">
                    @if(isset($content) && $content->thumbnail)
                        <img src="{{ Storage::url($content->thumbnail) }}" class="h-20 mt-3 rounded-lg border">
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div x-show="type === 'video'" x-cloak class="mb-4">
                    <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">URL YouTube <span class="text-red-500">*</span></label>
                    <input type="url" name="video_url" value="{{ old('video_url', $content->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                    @error('video_url') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-2">
                        <span x-text="type === 'video' ? 'Deskripsi Video (Opsional)' : 'Isi Konten *'"></span>
                    </label>
                    <textarea name="content" rows="12" class="w-full bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)] leading-relaxed" placeholder="Tulis konten menggunakan markdown atau teks biasa...">{{ old('content', $content->content ?? '') }}</textarea>
                    @error('content') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $content->is_published ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded text-[var(--tx-primary)] focus:ring-[var(--tx-primary)]">
                    <span class="text-sm font-bold text-[var(--tx-text-dark)]">Publish (Tampilkan ke User)</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $content->is_featured ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-bold text-[var(--tx-text-dark)]">Jadikan Pilihan Editor (Featured)</span>
                </label>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.dermatology.index') }}" class="px-8 py-3.5 rounded-full font-black text-sm uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-gradient px-10 py-3.5 text-sm">
                    {{ isset($content) ? 'Simpan Perubahan' : 'Publish Konten' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
