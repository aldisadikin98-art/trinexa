<x-admin-layout>
    <x-slot name="title">Voucher Belanja (Naturea)</x-slot>

    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-500 text-sm">Voucher ini khusus digunakan saat checkout belanja di Naturea Store.</p>
        <a href="{{ route('admin.voucher.create') }}" class="bg-[#0F2942] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1a3d5c] transition-colors flex items-center gap-2">
            + Buat Voucher Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vouchers as $voucher)
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-[#F5E6C8] rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-[#D4AF37]/20 text-[#9a7c1f] font-black tracking-widest px-3 py-1.5 rounded-xl text-lg border border-[#D4AF37]/30 border-dashed">
                        {{ $voucher->code }}
                    </div>
                    @if(!$voucher->is_active)
                        <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-full">Nonaktif</span>
                    @elseif($voucher->expired_at && $voucher->expired_at->isPast())
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full">Kedaluwarsa</span>
                    @else
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">Aktif</span>
                    @endif
                </div>

                <h3 class="font-bold text-[#0F2942] text-lg mb-1">{{ $voucher->name }}</h3>
                <p class="text-sm font-black text-[#D4AF37] mb-4">Diskon {{ $voucher->type === 'percent' ? $voucher->value . '%' : 'Rp ' . number_format($voucher->value, 0, ',', '.') }}</p>

                <div class="space-y-2 text-xs text-gray-600 bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-4">
                    <div class="flex justify-between">
                        <span>Min. Belanja</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</span>
                    </div>
                    @if($voucher->type === 'percent' && $voucher->max_discount)
                        <div class="flex justify-between">
                            <span>Maks. Diskon</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Batas Waktu</span>
                        <span class="font-bold {{ $voucher->expired_at && $voucher->expired_at->isPast() ? 'text-red-500' : 'text-gray-800' }}">
                            {{ $voucher->expired_at ? $voucher->expired_at->format('d M Y') : 'Tanpa batas' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sisa Kuota</span>
                        <span class="font-bold text-gray-800">
                            @if($voucher->quota)
                                {{ max(0, $voucher->quota - $voucher->used_count) }} dari {{ $voucher->quota }}
                            @else
                                Unlimited
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.voucher.edit', $voucher) }}" class="flex-1 text-center bg-gray-100 text-gray-700 font-bold py-2 rounded-xl hover:bg-gray-200 text-sm transition-colors">Edit</a>
                    <form action="{{ route('admin.voucher.destroy', $voucher) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus voucher ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-center border border-red-200 text-red-500 font-bold py-2 rounded-xl hover:bg-red-50 text-sm transition-colors">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $vouchers->links() }}
    </div>
</x-admin-layout>
