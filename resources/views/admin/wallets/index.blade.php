@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[var(--tx-text-dark)]">Saldo Harvestly User</h1>
            <p class="text-[var(--tx-text-muted)] font-bold text-sm">Monitor perputaran dana pengguna</p>
        </div>
    </div>

    <div class="glass-card rounded-[2.5rem] border border-white/60 overflow-hidden bg-white/40 shadow-xl">
        <div class="p-6 border-b border-white/60 bg-white/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('admin.wallets.index') }}" method="GET" class="relative w-full md:w-96">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                       class="w-full pl-12 pr-4 py-3 bg-white/60 border border-white/80 focus:border-[var(--tx-primary)] focus:ring-0 rounded-2xl text-sm font-bold shadow-sm">
                <span class="absolute left-4 top-3.5 opacity-40">🔍</span>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/30 text-[var(--tx-text-muted)] text-[10px] font-black uppercase tracking-widest border-b border-white/40">
                        <th class="px-8 py-5">Pemilik Saldo</th>
                        <th class="px-8 py-5 text-right">Saldo</th>
                        <th class="px-8 py-5 text-right">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/30">
                    @foreach($wallets as $wallet)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-8 py-5">
                            <div class="font-black text-[var(--tx-text-dark)]">{{ $wallet->user->name }}</div>
                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $wallet->user->email }}</div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="text-lg font-black text-[var(--tx-primary)]">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-8 py-5 text-right text-xs font-bold text-gray-500">
                            {{ $wallet->updated_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($wallets->hasPages())
        <div class="p-6 border-t border-white/40 bg-white/30">
            {{ $wallets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
