@extends('layouts.organizer')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-black text-slate-900">Dashboard {{ $organizer->organization_name }}</h1>
    <p class="text-slate-500 text-sm">Ringkasan event dan pendapatan organisasimu.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-slate-500 text-sm font-medium mb-1">Total Event</p>
        <p class="text-3xl font-black text-slate-900">{{ $events->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-slate-500 text-sm font-medium mb-1">Total Tiket Terjual</p>
        <p class="text-3xl font-black text-slate-900">{{ $events->sum('tickets_sold') }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-slate-500 text-sm font-medium mb-1">Total Pendapatan</p>
        <p class="text-3xl font-black text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
</div>

{{-- ============ GRAFIK SIMPLE (Fase 4 - Organizer) ============ --}}
@if($events->count() > 0)
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-10">
    <h3 class="font-black text-lg text-slate-900 mb-1">Performa per Event</h3>
    <p class="text-slate-400 text-sm mb-4">Tiket terjual & pendapatan tiap event</p>
    <canvas id="chartEventPerformance" height="90"></canvas>
</div>
@endif
{{-- ============ END GRAFIK ============ --}}

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="p-4 text-left text-sm font-bold text-slate-600">Event</th>
                <th class="p-4 text-left text-sm font-bold text-slate-600">Tanggal</th>
                <th class="p-4 text-left text-sm font-bold text-slate-600">Stok Tersisa</th>
                <th class="p-4 text-left text-sm font-bold text-slate-600">Tiket Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr class="border-t border-slate-100">
                    <td class="p-4 font-medium">{{ $event->title }}</td>
                    <td class="p-4 text-slate-500 text-sm">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</td>
                    <td class="p-4">{{ $event->stock }}</td>
                    <td class="p-4">{{ $event->tickets_sold }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-slate-400">Belum ada event yang kamu buat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($events->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
new Chart(document.getElementById('chartEventPerformance'), {
    type: 'bar',
    data: {
        labels: @json($events->pluck('title')),
        datasets: [
            {
                label: 'Tiket Terjual',
                data: @json($events->pluck('tickets_sold')),
                backgroundColor: '#4f46e5',
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Pendapatan (Rp)',
                data: @json($events->map(fn($e) => $e->revenue ?? 0)),
                backgroundColor: '#10b981',
                borderRadius: 6,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        scales: {
            y: { type: 'linear', position: 'left', title: { display: true, text: 'Tiket' } },
            y1: { type: 'linear', position: 'right', title: { display: true, text: 'Rupiah' }, grid: { drawOnChartArea: false } },
        }
    }
});
</script>
@endif
@endsection