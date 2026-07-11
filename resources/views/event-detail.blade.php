@extends('layouts.app')
@section('content')
<main class="max-w-7xl mx-auto px-6 py-12">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 font-medium mb-8">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Beranda</a>
        <span>/</span>
        <span class="text-slate-600">{{ $event->category->name ?? 'Event' }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

        {{-- LEFT: Content --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Poster --}}
            <div class="rounded-3xl overflow-hidden shadow-sm border border-slate-100">
                <img src="{{ ($event->poster_path && \Storage::disk('public')->exists($event->poster_path))
                        ? asset('storage/'.$event->poster_path)
                        : 'https://placehold.co/1200x600?text=No+Poster' }}"
                    alt="{{ $event->title }}"
                    class="w-full aspect-[16/9] object-cover">
            </div>

            {{-- Title & Meta --}}
            <div class="space-y-4">
                <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-wider">
                    {{ $event->category->name ?? 'Event' }}
                </span>
                <h1 class="text-3xl md:text-4xl font-black leading-tight text-slate-900">{{ $event->title }}</h1>

                <div class="flex flex-wrap gap-6 text-slate-500 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y - H:i') }} WIB</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            {{-- Organizer --}}
            <div class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold shrink-0">
                    {{ $event->partner ? strtoupper(substr($event->partner->name, 0, 2)) : 'EO' }}
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Diselenggarakan oleh</p>
                    <p class="font-bold text-slate-800">{{ $event->partner->name ?? 'AMIKOM Event Hub' }}</p>
                </div>
            </div>

            {{-- Description --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-slate-900">Deskripsi Event</h3>
                <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
            </div>

            {{-- Policy --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-slate-900">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis via email setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>
        </div>

        {{-- RIGHT: Sticky Price Card --}}
        <div class="lg:col-span-1">
            <div class="sticky top-28 bg-white rounded-3xl border border-slate-200 shadow-sm p-8 space-y-6">

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Harga Tiket</p>
                    <p class="text-4xl font-black text-indigo-600">
                        @if($event->price == 0)
                            Gratis
                        @else
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        @endif
                    </p>
                    <p class="text-xs text-slate-400 mt-1">/ orang, belum termasuk biaya layanan</p>
                </div>

                <div class="flex items-center gap-2 px-4 py-3 rounded-xl {{ $event->stock > 0 ? 'bg-emerald-50' : 'bg-rose-50' }}">
                    <svg class="w-5 h-5 {{ $event->stock > 0 ? 'text-emerald-500' : 'text-rose-500' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @if($event->stock > 0)
                        <span class="text-sm font-bold text-emerald-700">Sisa {{ $event->stock }} tiket lagi</span>
                    @else
                        <span class="text-sm font-bold text-rose-600">Tiket sudah habis</span>
                    @endif
                </div>

                @if($event->stock > 0)
                    <a href="{{ route('checkout.create', $event->id) }}"
                        class="block w-full text-center px-6 py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 transition-all">
                        Pesan Sekarang
                    </a>
                @else
                    <span class="block w-full text-center px-6 py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-lg cursor-not-allowed">
                        Tiket Habis
                    </span>
                @endif

                <div class="pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Pembayaran aman & terenkripsi via Midtrans
                </div>
            </div>
        </div>

    </div>
</main>
@endsection