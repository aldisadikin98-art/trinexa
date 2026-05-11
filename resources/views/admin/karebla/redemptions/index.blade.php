<x-admin-layout title="Kelola Penukaran Karebla">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <h2 class="text-2xl font-bold text-gray-800">Kelola Penukaran Karebla</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between bg-gray-50/50">
                    <form action="{{ route('admin.karebla.penukaran.index') }}" method="GET" class="flex gap-2 w-full max-w-md">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Penukaran..." class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700">Cari</button>
                    </form>

                    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide w-full md:w-auto">
                        <a href="{{ route('admin.karebla.penukaran.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap {{ !request('status') ? 'bg-[#0F2942] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Semua</a>
                        @foreach($statusOptions as $val => $label)
                            <a href="{{ route('admin.karebla.penukaran.index', ['status' => $val, 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap {{ request('status') == $val ? 'bg-[#0F2942] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">No. Penukaran</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($redemptions as $item)
                                @php
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'diproses' => 'bg-blue-100 text-blue-800',
                                        'dikirim'  => 'bg-purple-100 text-purple-800',
                                        'selesai'  => 'bg-green-100 text-green-800',
                                    ];
                                    $statusColor = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ $item->receipt_number }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-800">{{ $item->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @php $images = $item->product->images ?? []; @endphp
                                            <img src="{{ $images[0] ?? '' }}" alt="" class="w-8 h-8 rounded object-cover bg-gray-100 border border-gray-200">
                                            <div>
                                                <p class="font-bold text-gray-800 max-w-[150px] truncate" title="{{ $item->product->name }}">{{ $item->product->name }}</p>
                                                <p class="text-[10px] font-bold text-[#D4AF37]">{{ number_format($item->coins_used, 0, ',', '.') }} Koin</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg uppercase tracking-wider {{ $statusColor }}">{{ $item->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.karebla.penukaran.show', $item->id) }}" class="text-blue-600 hover:underline font-semibold bg-blue-50 px-3 py-1.5 rounded-lg">Kelola</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if($redemptions->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data penukaran.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-100">
                    {{ $redemptions->links() }}
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
