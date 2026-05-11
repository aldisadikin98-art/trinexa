<x-admin-layout>
    <x-slot name="title">{{ isset($product) ? 'Edit Produk: ' . $product->name : 'Tambah Produk Baru' }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.produk.index') }}" class="font-bold text-gray-500 hover:text-gray-800">← Kembali</a>
    </div>

    <form action="{{ isset($product) ? route('admin.produk.update', $product) : route('admin.produk.store') }}" 
          method="POST" enctype="multipart/form-data" 
          class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-4xl">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Nama --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
                    <option value="">Pilih Kategori...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $product->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- BPOM --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor BPOM</label>
                <input type="text" name="bpom_number" value="{{ old('bpom_number', $product->bpom_number ?? '') }}"
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', isset($product) ? intval($product->price) : '') }}" required min="0"
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
            </div>

            {{-- Stok --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required min="0"
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
            </div>
        </div>

        <hr class="border-gray-100 my-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Image URL --}}
            @if(!isset($product))
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Image URL (Unsplash/Dummy)</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}"
                           class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]" placeholder="https://images.unsplash.com/...">
                </div>
            @endif

            {{-- Upload Image --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Upload Foto (Bisa pilih banyak)</label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full border border-gray-200 rounded-xl p-2 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#D4AF37]/10 file:text-[#9a7c1f] hover:file:bg-[#D4AF37]/20">
                @if(isset($product) && !empty($product->images))
                    <div class="flex gap-2 mt-3">
                        @foreach($product->images as $img)
                            <img src="{{ $img }}" class="w-16 h-16 rounded-lg object-cover">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <hr class="border-gray-100 my-8">

        <div class="space-y-6 mb-8">
            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                <textarea name="description" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kandungan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kandungan Utama (Pisahkan dengan Enter)</label>
                    <textarea name="ingredients" rows="4" placeholder="Niacinamide&#10;Centella Asiatica" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">{{ old('ingredients', isset($product) && $product->ingredients ? implode("\n", $product->ingredients) : '') }}</textarea>
                </div>

                {{-- Manfaat --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Manfaat</label>
                    <textarea name="benefits" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">{{ old('benefits', $product->benefits ?? '') }}</textarea>
                </div>
            </div>

            {{-- Cara Pakai --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Cara Pakai</label>
                <textarea name="usage_instructions" rows="3" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">{{ old('usage_instructions', $product->usage_instructions ?? '') }}</textarea>
            </div>

            {{-- Jenis Kulit --}}
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <label class="block text-sm font-bold text-gray-700 mb-3">Cocok untuk Jenis Kulit</label>
                <div class="flex flex-wrap gap-4">
                    @php $selectedSkins = old('skin_type', $product->skin_type ?? []); @endphp
                    @foreach(['Normal', 'Kering', 'Berminyak', 'Kombinasi', 'Sensitif', 'Berjerawat'] as $skin)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="skin_type[]" value="{{ $skin }}" {{ in_array($skin, (array)$selectedSkins) ? 'checked' : '' }} class="accent-[#D4AF37] w-4 h-4 rounded">
                            <span class="text-sm text-gray-700">{{ $skin }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-600 mb-2">Tidak cocok untuk (Opsional)</label>
                    <input type="text" name="skin_type_not_suitable" value="{{ old('skin_type_not_suitable', $product->skin_type_not_suitable ?? '') }}" placeholder="Contoh: Kulit yang sedang iritasi parah"
                           class="w-full border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#D4AF37]">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.produk.index') }}" class="px-6 py-3 font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</a>
            <button type="submit" class="px-8 py-3 font-bold text-white bg-[#0F2942] rounded-xl hover:bg-[#1a3d5c]">
                {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}
            </button>
        </div>
    </form>
</x-admin-layout>
