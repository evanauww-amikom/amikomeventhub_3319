@extends('layouts.organizer', ['title' => 'Kelola Event'])

@section('content')
<header class="mb-10 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-black text-slate-800">Kelola Event</h1>
        <p class="text-slate-500 font-medium">Event yang kamu selenggarakan.</p>
    </div>
    <a href="{{ route('organizer.events.create') }}"
       class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
        + Tambah Event
    </a>
</header>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-xl text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="p-5 text-left text-sm font-bold text-slate-600">Judul</th>
                <th class="p-5 text-left text-sm font-bold text-slate-600">Kategori</th>
                <th class="p-5 text-left text-sm font-bold text-slate-600">Tanggal</th>
                <th class="p-5 text-left text-sm font-bold text-slate-600">Stok</th>
                <th class="p-5 text-left text-sm font-bold text-slate-600">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr class="border-t border-slate-100">
                    <td class="p-5 font-medium">{{ $event->title }}</td>
                    <td class="p-5 text-sm text-slate-500">{{ $event->category->name ?? '-' }}</td>
                    <td class="p-5 text-sm text-slate-500">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y, H:i') }}</td>
                    <td class="p-5">{{ $event->stock }}</td>
                    <td class="p-5 flex gap-3">
                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-indigo-600 text-sm font-bold">Edit</a>
                        <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 text-sm font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-10 text-center text-slate-400">Belum ada event yang kamu buat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection