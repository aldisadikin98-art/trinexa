@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black text-gray-800">Broadcast Pemberitahuan</h1>
        <button onclick="document.getElementById('modal-notify').classList.remove('hidden')" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
            + Kirim Notifikasi
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Target</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Judul</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Tipe</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($notifications as $notif)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($notif->user_id)
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">{{ $notif->user->name }}</span>
                        @else
                            <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold">Seluruh Pengguna</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $notif->title }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                            @if($notif->type == 'info') bg-blue-100 text-blue-700
                            @elseif($notif->type == 'success') bg-green-100 text-green-700
                            @elseif($notif->type == 'warning') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $notif->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.notifications.destroy', $notif) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 font-bold text-xs uppercase tracking-widest">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

{{-- Modal Send --}}
<div id="modal-notify" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 hidden">
    <div class="bg-white rounded-[2rem] w-full max-w-lg p-8 shadow-2xl animate-scale-in">
        <h3 class="text-xl font-black text-gray-800 mb-6">Kirim Pemberitahuan Baru</h3>
        
        <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Target Penerima</label>
                <select name="target" onchange="toggleUserSelect(this.value)" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold">
                    <option value="all">Seluruh Pengguna</option>
                    <option value="specific">Pengguna Spesifik</option>
                </select>
            </div>

            <div id="user-select" class="hidden">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Pengguna</label>
                <select name="user_id" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Judul</label>
                <input type="text" name="title" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold" placeholder="Contoh: Promo Gajian Naturea!">
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pesan</label>
                <textarea name="message" required rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold" placeholder="Masukkan isi pesan..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tipe</label>
                    <select name="type" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold">
                        <option value="info">Info (Biru)</option>
                        <option value="success">Success (Hijau)</option>
                        <option value="warning">Warning (Kuning)</option>
                        <option value="danger">Danger (Merah)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link (Opsional)</label>
                    <input type="text" name="link" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold" placeholder="https://...">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('modal-notify').classList.add('hidden')" class="flex-1 px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-colors uppercase tracking-widest text-xs">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 uppercase tracking-widest text-xs">Kirim Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleUserSelect(val) {
        document.getElementById('user-select').classList.toggle('hidden', val !== 'specific');
    }
</script>
@endsection
