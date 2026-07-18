@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wide mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wide mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black text-slate-900">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wide mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black text-slate-900">{{ $activeEvents }} Event</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wide mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black text-slate-900">{{ $pendingOrders }} Pesanan</h3>
    </div>

</div>

{{-- ============ GRAFIK (Fase 4) ============ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm lg:col-span-2">
        <h3 class="font-black text-lg text-slate-900 mb-1">Pertumbuhan User</h3>
        <p class="text-slate-400 text-sm mb-4">Jumlah user baru per bulan (6 bulan terakhir)</p>
        <canvas id="chartUserGrowth" height="100"></canvas>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <h3 class="font-black text-lg text-slate-900 mb-1">Pertumbuhan Event</h3>
        <p class="text-slate-400 text-sm mb-4">Event baru per bulan</p>
        <canvas id="chartEventGrowth" height="150"></canvas>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm lg:col-span-3">
        <h3 class="font-black text-lg text-slate-900 mb-1">Revenue per Organizer</h3>
        <p class="text-slate-400 text-sm mb-4">Top 10 organizer berdasarkan pendapatan sukses</p>
        <canvas id="chartRevenueOrganizer" height="80"></canvas>
    </div>

</div>
{{-- ============ END GRAFIK ============ --}}

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="font-black text-xl text-slate-900">Transaksi Terakhir</h3>
            <p class="text-slate-400 text-sm">5 transaksi paling baru masuk</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="text-sm px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <th class="px-8 py-4">Tgl Transaksi</th>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-5 text-sm text-slate-600">
                        {{ $trx->created_at->format('d M y, H:i') }}
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $trx->order_id }}</p>
                    </td>
                    <td class="px-8 py-5">
                        <p class="font-bold text-slate-800 text-sm truncate max-w-[150px]">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-[150px]">{{ $trx->customer_email }}</p>
                    </td>
                    <td class="px-8 py-5 text-sm text-slate-600 truncate max-w-[180px]">{{ $trx->event->title ?? '-' }}</td>
                    <td class="px-8 py-5">
                        @if($trx->status === 'Success')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Success</span>
                        @elseif($trx->status === 'Pending')
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right font-black text-indigo-600">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-14 text-center text-slate-400">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
fetch("{{ route('admin.dashboard.chart-data') }}")
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById('chartUserGrowth'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'User Baru',
                    data: data.userGrowth,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,0.1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('chartEventGrowth'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Event Baru',
                    data: data.eventGrowth,
                    backgroundColor: '#f97316',
                    borderRadius: 6,
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('chartRevenueOrganizer'), {
            type: 'bar',
            data: {
                labels: data.organizerNames,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: data.organizerRevenue,
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } }
            }
        });
    })
    .catch(err => console.error('Gagal load chart data:', err));
</script>
@endpush