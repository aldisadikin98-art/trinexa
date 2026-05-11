<x-admin-layout>
    <x-slot name="title">Kelola Pesanan</x-slot>

    <div class="flex justify-between items-center mb-6">
        <form method="GET" action="{{ route('admin.pesanan.index') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no resi..."
                   class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#D4AF37]">
            <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#D4AF37]">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-[#0F2942] text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#1a3d5c]">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.pesanan.index') }}" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-red-500">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">No. Resi</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-[#0F2942]">{{ $order->receipt_number }}</td>
                            <td class="px-6 py-4">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.pesanan.show', $order) }}" class="text-blue-600 hover:underline font-bold bg-blue-50 px-3 py-1.5 rounded-lg">Kelola</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 font-medium">Belum ada pesanan yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
