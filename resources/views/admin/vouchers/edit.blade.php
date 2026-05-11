<x-admin-layout>
    <x-slot name="title">{{ isset($voucher) ? 'Edit Voucher: ' . $voucher->code : 'Buat Voucher Baru' }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.voucher.index') }}" class="font-bold text-gray-500 hover:text-gray-800">← Kembali</a>
    </div>

    <form action="{{ isset($voucher) ? route('admin.voucher.update', $voucher) : route('admin.voucher.store') }}" 
          method="POST" 
          class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl" x-data="{ type: '{{ old('type', $voucher->type ?? 'nominal') }}' }">
        @csrf
        @if(isset($voucher)) @method('PUT') @endif

        <div class="space-y-6">
            {{-- Kode & Status --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode Voucher <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $voucher->code ?? '') }}" required uppercase maxlength="20"
                           class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37] uppercase font-bold"
                           {{ isset($voucher) ? 'readonly bg-gray-100' : '' }} placeholder="NATUREA2026">
                    @if(isset($voucher))
                        <p class="text-[10px] text-gray-400 mt-1">Kode voucher tidak bisa diubah.</p>
                    @endif
                    @error('code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
                        <option value="1" {{ old('is_active', $voucher->is_active ?? true) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $voucher->is_active ?? true) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama / Deskripsi Singkat <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $voucher->name ?? '') }}" required
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]" placeholder="Diskon Grand Launching">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <hr class="border-gray-100 my-6">

            {{-- Nilai Diskon --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Diskon <span class="text-red-500">*</span></label>
                    <select name="type" x-model="type" required class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
                        <option value="nominal">Nominal (Rupiah)</option>
                        <option value="percent">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Besaran Diskon <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 font-bold" x-show="type === 'nominal'">Rp</span>
                        <input type="number" name="value" value="{{ old('value', isset($voucher) ? intval($voucher->value) : '') }}" required min="1"
                               class="w-full border-gray-200 rounded-xl py-2.5 focus:ring-[#D4AF37]"
                               :class="type === 'nominal' ? 'pl-9 pr-4' : 'px-4'">
                        <span class="absolute right-4 top-2.5 text-gray-400 font-bold" x-show="type === 'percent'">%</span>
                    </div>
                </div>
            </div>

            {{-- Aturan Belanja --}}
            <div class="grid grid-cols-2 gap-6 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimal Belanja (Rp)</label>
                    <input type="number" name="min_purchase" value="{{ old('min_purchase', isset($voucher) ? intval($voucher->min_purchase) : 0) }}" min="0"
                           class="w-full border-gray-200 rounded-xl px-4 py-2 focus:ring-[#D4AF37]" placeholder="0">
                </div>
                <div x-show="type === 'percent'">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Maksimal Diskon (Rp)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount', isset($voucher) ? intval($voucher->max_discount) : '') }}" min="0"
                           class="w-full border-gray-200 rounded-xl px-4 py-2 focus:ring-[#D4AF37]" placeholder="Opsional (Kosongkan jika tanpa batas)">
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            {{-- Batasan --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="expired_at" value="{{ old('expired_at', isset($voucher) && $voucher->expired_at ? $voucher->expired_at->format('Y-m-d') : '') }}"
                           class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]">
                    <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika tidak ada kedaluwarsa.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Batas Kuota Pemakaian</label>
                    <input type="number" name="quota" value="{{ old('quota', $voucher->quota ?? '') }}" min="1"
                           class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#D4AF37]" placeholder="Kosongkan jika unlimited">
                    @if(isset($voucher))
                        <p class="text-[10px] text-gray-400 mt-1">Sudah terpakai: {{ $voucher->used_count }} kali.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.voucher.index') }}" class="px-6 py-3 font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</a>
            <button type="submit" class="px-8 py-3 font-bold text-white bg-[#0F2942] rounded-xl hover:bg-[#1a3d5c]">
                {{ isset($voucher) ? 'Simpan Perubahan' : 'Buat Voucher' }}
            </button>
        </div>
    </form>
</x-admin-layout>
