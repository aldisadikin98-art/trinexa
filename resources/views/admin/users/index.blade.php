@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[var(--tx-text-dark)]">Manajemen Pengguna</h1>
            <p class="text-[var(--tx-text-muted)] font-bold text-sm">Daftar seluruh pengguna aktif di Trinexa</p>
        </div>
    </div>

    <div class="glass-card rounded-[2.5rem] border border-white/60 overflow-hidden bg-white/40 shadow-xl">
        <div class="p-6 border-b border-white/60 bg-white/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full md:w-96">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                       class="w-full pl-12 pr-4 py-3 bg-white/60 border border-white/80 focus:border-[var(--tx-primary)] focus:ring-0 rounded-2xl text-sm font-bold shadow-sm">
                <span class="absolute left-4 top-3.5 opacity-40">🔍</span>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/30 text-[var(--tx-text-muted)] text-[10px] font-black uppercase tracking-widest border-b border-white/40">
                        <th class="px-8 py-5">User</th>
                        <th class="px-8 py-5">Email</th>
                        <th class="px-8 py-5">Level</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/30">
                    @foreach($users as $user)
                    <tr class="hover:bg-white/40 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] border border-white shadow-sm flex items-center justify-center text-sm font-black text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-black text-[var(--tx-text-dark)]">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 font-bold text-gray-500 text-sm">{{ $user->email }}</td>
                        <td class="px-8 py-5">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-white border border-white shadow-sm text-[var(--tx-primary)]">
                                {{ $user->loyalty_level }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button class="text-[10px] font-black text-[var(--tx-primary)] uppercase tracking-widest hover:underline bg-white/60 px-4 py-2 rounded-xl border border-white shadow-sm">Detail</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-6 border-t border-white/40 bg-white/30">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
