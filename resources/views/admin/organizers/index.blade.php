@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Kelola Organizer</h1>
    <p class="text-slate-500 text-sm">Verifikasi kepanitiaan/HIMA yang mendaftar sebagai penyelenggara.</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-xl text-sm">{{ session('success') }}</div>
@endif

<table class="w-full bg-white rounded-xl shadow-sm overflow-hidden">
    <thead class="bg-slate-100">
        <tr>
            <th class="p-4 text-left">Organisasi</th>
            <th class="p-4 text-left">Penanggung Jawab</th>
            <th class="p-4 text-left">Email</th>
            <th class="p-4 text-left">Status</th>
            <th class="p-4 text-left">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($organizers as $organizer)
            <tr class="border-b">
                <td class="p-4 font-medium">{{ $organizer->organization_name }}</td>
                <td class="p-4">{{ $organizer->user->name }}</td>
                <td class="p-4 text-sm text-slate-500">{{ $organizer->user->email }}</td>
                <td class="p-4">
                    @if($organizer->status === 'approved')
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">Approved</span>
                    @elseif($organizer->status === 'rejected')
                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold">Rejected</span>
                    @else
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold">Pending</span>
                    @endif
                </td>
                <td class="p-4 flex gap-2">
                    @if($organizer->status !== 'approved')
                        <form action="{{ route('admin.organizers.approve', $organizer) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-emerald-600 text-sm font-bold">Setujui</button>
                        </form>
                    @endif
                    @if($organizer->status !== 'rejected')
                        <form action="{{ route('admin.organizers.reject', $organizer) }}" method="POST" onsubmit="return confirm('Tolak organizer ini?')">
                            @csrf
                            <button type="submit" class="text-rose-600 text-sm font-bold">Tolak</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-8 text-center text-slate-400">Belum ada organizer yang mendaftar.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection