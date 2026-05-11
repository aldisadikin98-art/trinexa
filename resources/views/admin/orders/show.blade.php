<x-admin-layout>
    <x-slot name="title">Detail Pesanan: {{ $transaction->receipt_number }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.pesanan.index') }}" class="font-bold text-gray-500 hover:text-gray-800">← Kembali</a>
        <span class="px-4 py-2 rounded-full text-sm font-black {{ $transaction->status_color }}">{{ $transaction->status_label }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kiri: Detail Produk & Pembeli --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-extrabold text-[#0F2942] mb-4 text-lg">Informasi Pembeli</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Nama</p>
                        <p class="font-bold">{{ $transaction->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Email</p>
                        <p class="font-bold">{{ $transaction->user->email }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500 mb-1">Alamat Pengiriman</p>
                        <p class="font-bold bg-gray-50 p-3 rounded-xl border border-gray-100">{{ $transaction->shipping_address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-extrabold text-[#0F2942] mb-4 text-lg">Produk yang Dipesan</h3>
                <div class="space-y-4">
                    @foreach($transaction->items as $item)
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            <img src="{{ $item->product->primary_image }}" class="w-16 h-16 rounded-xl object-cover">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="font-black text-[#0F2942]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-extrabold text-[#0F2942] mb-4 text-lg">Rincian Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal Produk</span>
                        <span>Rp {{ number_format($transaction->items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Voucher ({{ $transaction->shopVoucher->code ?? '-' }})</span>
                            <span class="font-bold">− Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <hr class="border-gray-100 my-2">
                    <div class="flex justify-between font-black text-lg text-[#0F2942]">
                        <span>Total Bayar (Wallet)</span>
                        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Update Status --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-extrabold text-[#0F2942] mb-4 text-lg">Update Status</h3>
                
                @if(in_array($transaction->status, ['dibatalkan', 'selesai']))
                    <div class="bg-gray-50 text-gray-500 p-4 rounded-xl text-center text-sm font-bold border border-gray-100">
                        Pesanan ini sudah {{ ucfirst($transaction->status) }} dan tidak dapat diubah lagi.
                    </div>
                @else
                    <form action="{{ route('admin.pesanan.status', $transaction) }}" method="POST" class="space-y-4">
                        @csrf @method('PATCH')
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Pesanan</label>
                            <select name="status" class="w-full border-gray-200 rounded-xl px-4 py-2 focus:ring-[#D4AF37]">
                                <option value="diproses" {{ $transaction->status === 'pending' ? 'selected' : '' }}>📦 Diproses</option>
                                <option value="dikirim" {{ $transaction->status === 'diproses' ? 'selected' : '' }}>🚚 Dikirim</option>
                                <option value="selesai" {{ $transaction->status === 'dikirim' ? 'selected' : '' }}>✅ Selesai</option>
                            </select>
                            <p class="text-[10px] text-gray-500 mt-1">Mengubah ke 'Selesai' akan otomatis memberikan koin ke user.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Resi Kurir (Opsional)</label>
                            <input type="text" name="tracking_number" value="{{ $transaction->tracking_number }}" 
                                   class="w-full border-gray-200 rounded-xl px-4 py-2 focus:ring-[#D4AF37]" placeholder="Contoh: JNT123456789">
                        </div>

                        <button type="submit" class="w-full bg-[#0F2942] text-white font-bold py-3 rounded-xl hover:bg-[#1a3d5c] transition-colors">
                            Simpan Perubahan
                        </button>
                    </form>
                @endif
            </div>

            @if($transaction->canBeCancelled())
                <div class="bg-red-50 rounded-3xl border border-red-100 p-6 shadow-sm">
                    <h3 class="font-extrabold text-red-800 mb-2">Batalkan Paksa</h3>
                    <form action="{{ route('admin.pesanan.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan ini? Saldo user akan dikembalikan otomatis.');">
                        @csrf @method('PATCH')
                        <input type="hidden" name="reason" value="Dibatalkan oleh Admin">
                        <button type="submit" class="w-full bg-red-100 text-red-700 font-bold py-3 rounded-xl hover:bg-red-200 transition-colors text-sm">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
