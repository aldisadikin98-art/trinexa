<x-admin-layout title="Detail Penukaran {{ $redemption->receipt_number }}">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Detail Penukaran Karebla</h2>
                <a href="{{ route('admin.karebla.penukaran.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-semibold">&larr; Kembali</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kiri: Detail Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-[#0F2942] p-6 text-white flex justify-between items-center bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
                            <div>
                                <p class="text-[10px] font-bold text-[#F5E6C8] uppercase tracking-widest mb-1">No. Penukaran</p>
                                <h3 class="text-xl font-black">{{ $redemption->receipt_number }}</h3>
                            </div>
                            @php
                                $statusColors = [
                                    'menunggu' => 'bg-yellow-500',
                                    'diproses' => 'bg-blue-500',
                                    'dikirim'  => 'bg-purple-500',
                                    'selesai'  => 'bg-green-500',
                                ];
                                $statusColor = $statusColors[$redemption->status] ?? 'bg-gray-500';
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-lg uppercase tracking-wider text-white {{ $statusColor }} shadow-sm">
                                {{ $redemption->status }}
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <h4 class="font-bold text-gray-800 mb-4 uppercase tracking-wider text-xs border-b pb-2">Produk Ditukar</h4>
                            <div class="flex items-start gap-4 mb-8">
                                @php $images = $redemption->product->images ?? []; @endphp
                                <img src="{{ $images[0] ?? '' }}" alt="" class="w-20 h-20 rounded-xl object-cover border border-gray-200">
                                <div class="flex-1">
                                    <h5 class="font-bold text-gray-800 text-lg mb-1">{{ $redemption->product->name }}</h5>
                                    <p class="text-xs text-gray-500 mb-2">{{ $redemption->product->collection }}</p>
                                    <span class="font-black text-[#D4AF37] bg-[#D4AF37]/10 px-3 py-1 rounded-lg text-sm">-{{ number_format($redemption->coins_used, 0, ',', '.') }} Koin</span>
                                </div>
                            </div>

                            <h4 class="font-bold text-gray-800 mb-4 uppercase tracking-wider text-xs border-b pb-2">Info Pengiriman</h4>
                            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                <div>
                                    <span class="text-gray-500 block mb-1">Penerima</span>
                                    <span class="font-semibold text-gray-800">{{ $redemption->user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block mb-1">No HP</span>
                                    <span class="font-semibold text-gray-800">{{ $redemption->user->phone ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block mb-1">Email</span>
                                    <span class="font-semibold text-gray-800">{{ $redemption->user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block mb-1">Tanggal Request</span>
                                    <span class="font-semibold text-gray-800">{{ $redemption->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            <div class="text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <span class="text-gray-500 block mb-1 font-semibold uppercase text-xs">Alamat Lengkap</span>
                                <p class="text-gray-800">{{ $redemption->shipping_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Update Status --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="font-bold text-gray-800 mb-4 uppercase tracking-wider text-xs border-b pb-2">Update Status</h4>
                        
                        <form action="{{ route('admin.karebla.penukaran.status', $redemption->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Status Baru</label>
                                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-[#0F2942] focus:ring focus:ring-[#0F2942] focus:ring-opacity-50 text-sm">
                                    <option value="menunggu" {{ $redemption->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ $redemption->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $redemption->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="selesai" {{ $redemption->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Admin (Bisa dilihat User)</label>
                                <textarea name="notes" rows="3" placeholder="Misal: Resi JNE 123456789" class="w-full rounded-lg border-gray-300 focus:border-[#0F2942] focus:ring focus:ring-[#0F2942] focus:ring-opacity-50 text-sm">{{ $redemption->notes }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-[#0F2942] hover:bg-[#15385a] text-white px-4 py-2.5 rounded-xl font-bold shadow-md transition">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl text-yellow-800 text-sm">
                        <p class="font-bold mb-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Penting
                        </p>
                        <p>Penukaran ini bersifat <strong>final</strong>. Koin pengguna ({{ number_format($redemption->coins_used, 0, ',', '.') }}) sudah otomatis terpotong saat pesanan dibuat dan tidak bisa dibatalkan dari sistem.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-admin-layout>
