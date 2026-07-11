@extends('layouts.admin')

@section('title', 'Kelola Event - Admin')
@section('page_title', 'Kelola Event')
@section('page_subtitle', 'Buat dan atur acara seru Anda di sini.')

@section('content')

<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Daftar Event</h1>
        <p class="text-slate-400 text-sm">{{ $events->count() }} event terdaftar</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Event Baru
    </a>
</header>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">No</th>
                    <th class="px-8 py-4">Poster</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Harga</th>
                    <th class="px-8 py-4">Stok</th>
                    <th class="px-8 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($events as $index => $event)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <img src="{{ ($event->poster_path && \Storage::disk('public')->exists($event->poster_path))
                                ? asset('storage/'.$event->poster_path)
                                : 'https://placehold.co/100x120?text=No+Img' }}"
                             class="w-14 h-18 rounded-xl object-cover shadow-sm">
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-800">{{ $event->title }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $event->category->name ?? '-' }} • {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold text-indigo-600">
                            @if($event->price == 0)
                                Gratis
                            @else
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            @endif
                        </p>
                    </td>
                    <td class="px-8 py-6">
                        @if($event->stock > 10)
                            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold">{{ $event->stock }} tersedia</span>
                        @elseif($event->stock > 0)
                            <span class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg text-xs font-bold">{{ $event->stock }} tersisa — menipis</span>
                        @else
                            <span class="px-3 py-1.5 bg-rose-50 text-rose-700 rounded-lg text-xs font-bold">Habis</span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event \'{{ $event->title }}\'? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-14 text-center text-slate-400">
                        Belum ada event. <a href="{{ route('admin.events.create') }}" class="text-indigo-600 font-bold hover:underline">Buat event pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection