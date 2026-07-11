@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Admin')
@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')

@section('content')

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Order ID</th>
                    <th class="px-8 py-4">Detail Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Tgl Transaksi</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total Tagihan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/70 transition {{ $trx->status === 'Pending' ? 'opacity-60' : '' }}">
                        <td class="px-8 py-6">
                            <span class="font-mono font-bold px-3 py-1.5 rounded-lg text-sm {{ $trx->status === 'Pending' ? 'bg-slate-100 text-slate-500' : 'bg-indigo-50 text-indigo-600' }}">
                                {{ $trx->order_id }}
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <p class="font-bold text-slate-800">{{ $trx->customer_name }}</p>
                            <p class="text-xs text-slate-500">{{ $trx->customer_email }}</p>
                            <p class="text-xs text-slate-400">{{ $trx->customer_phone }}</p>
                        </td>

                        <td class="px-8 py-6">
                            <p class="font-medium text-slate-700">{{ $trx->event->title ?? '-' }}</p>
                        </td>

                        <td class="px-8 py-6 text-sm text-slate-500">
                            {{ $trx->created_at->format('d M Y, H:i') }}
                        </td>

                        <td class="px-8 py-6">
                            @if($trx->status === 'Success')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase ring-1 ring-emerald-200">Success</span>
                            @elseif($trx->status === 'Pending')
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase ring-1 ring-orange-200">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200">{{ $trx->status }}</span>
                            @endif
                        </td>

                        <td class="px-8 py-6 text-right font-black {{ $trx->status === 'Pending' ? 'text-slate-400' : 'text-slate-900' }}">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-14 text-center text-slate-400">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

@endsection