<x-admin-layout title="Tambah Produk Karebla">
    <div class="py-12" x-data="specManager([])">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Produk Karebla</h2>
                <a href="{{ route('admin.karebla.produk.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-semibold">&larr; Kembali</a>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.karebla.produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf
                <div class="p-6 md:p-8 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Koleksi (Opsional)</label>
                            <input type="text" name="collection" value="{{ old('collection') }}" placeholder="Misal: Karebla Premium Collection" class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Koin</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-bold">🪙</span>
                                <input type="number" name="coin_price" value="{{ old('coin_price') }}" min="1" required class="w-full pl-9 rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Badge</label>
                            <select name="badge" class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                                <option value="">Tidak ada</option>
                                <option value="eksklusif" {{ old('badge') == 'eksklusif' ? 'selected' : '' }}>Eksklusif ✨</option>
                                <option value="terlaris" {{ old('badge') == 'terlaris' ? 'selected' : '' }}>Terlaris 🔥</option>
                                <option value="baru" {{ old('badge') == 'baru' ? 'selected' : '' }}>Baru 🆕</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="4" required class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesifikasi Tambahan (Opsional)</label>
                        <div class="space-y-3">
                            <template x-for="(spec, index) in specs" :key="index">
                                <div class="flex gap-3 items-center">
                                    <input type="text" :name="'specs_keys['+index+']'" x-model="spec.key" placeholder="Key (Misal: Material)" class="w-1/3 rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                                    <input type="text" :name="'specs_values['+index+']'" x-model="spec.value" placeholder="Value (Misal: Katun)" class="flex-1 rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                                    <button type="button" @click="removeSpec(index)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addSpec" class="mt-3 text-sm font-bold text-[#D4AF37] hover:underline">+ Tambah Spesifikasi</button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Produk</label>
                        <input type="file" name="images[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#F5E6C8] file:text-[#9a7c1f] hover:file:bg-[#e9d9b0] transition-all">
                        <p class="text-[10px] text-gray-400 mt-2 italic">*Bisa pilih lebih dari satu gambar. Rekomendasi rasio 1:1.</p>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-[#0F2942] focus:ring-[#D4AF37]">
                            <span class="text-sm font-semibold text-gray-700">Produk Aktif (Tampil di Katalog)</span>
                        </label>
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-[#0F2942] hover:bg-[#15385a] text-white px-6 py-2.5 rounded-xl font-bold shadow-md transition">
                        Simpan Produk
                    </button>
                </div>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('specManager', (initialSpecs) => ({
                specs: initialSpecs.length ? initialSpecs : [{key: '', value: ''}],
                addSpec() {
                    this.specs.push({key: '', value: ''});
                },
                removeSpec(index) {
                    this.specs.splice(index, 1);
                }
            }));
        });
    </script>
    @endpush
</x-admin-layout>
