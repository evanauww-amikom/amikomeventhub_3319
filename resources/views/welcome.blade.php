@extends('layouts.app')

@section('content')

{{-- HERO (compact) --}}
<section class="max-w-7xl mx-auto px-6 pt-12 pb-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm px-8 py-10 md:px-14 md:py-14 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="space-y-4 max-w-xl">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">
                #1 Event Platform
            </span>
            <h1 class="text-3xl md:text-4xl font-black leading-tight text-slate-900">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu
            </h1>
            <p class="text-slate-500 leading-relaxed">
                Dari konser musik hingga workshop teknologi — pesan aman & cepat dengan Midtrans.
            </p>
        </div>
        <a href="#events"
            class="shrink-0 px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:scale-105 transition-all whitespace-nowrap">
            Mulai Jelajah
        </a>
    </div>
</section>

{{-- EVENT LIST --}}
<section id="events" class="max-w-7xl mx-auto px-6 pb-20">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-1">Event Terdekat</h2>
            <p class="text-slate-500 font-medium text-sm">Jangan sampai ketinggalan acara seru minggu ini!</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('home') }}#events"
               class="px-4 py-2 rounded-xl text-sm font-bold transition {{ !request()->has('category') || request('category') == '' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-600 hover:text-indigo-600' }}">
                Semua
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->id]) }}#events"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request('category') == $category->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-600 hover:text-indigo-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="relative overflow-hidden aspect-[4/3]">
                    <img src="{{ ($event->poster_path && \Storage::disk('public')->exists($event->poster_path))
                            ? asset('storage/' . $event->poster_path)
                            : 'https://placehold.co/600x450?text=No+Poster' }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                        {{ $event->category->name ?? 'General' }}
                    </div>

                    @if($event->stock <= 0)
                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                            <span class="px-4 py-1.5 bg-rose-600 text-white rounded-full text-xs font-bold uppercase tracking-wide">Tiket Habis</span>
                        </div>
                    @endif
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition line-clamp-1">
                            {{ $event->title }}
                        </h3>
                        <div class="flex items-center gap-2 text-slate-500 text-sm mt-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    @if($event->stock > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">
                            Sisa {{ $event->stock }} tiket
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold">
                            Habis terjual
                        </span>
                    @endif

                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <span class="text-xl font-black text-indigo-600">
                            @if($event->price == 0)
                                Gratis
                            @else
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            @endif
                        </span>

                        <a href="{{ route('events.show', $event->id) }}"
                           class="px-5 py-2.5 {{ $event->stock > 0 ? 'bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white' : 'bg-slate-100 text-slate-400' }} rounded-xl font-bold text-sm transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <p class="text-slate-400 font-medium">Belum ada event tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- PARTNERS --}}
<section class="max-w-7xl mx-auto px-6 py-12 border-t border-slate-100">
    <p class="text-center text-xs font-bold uppercase tracking-widest text-indigo-600 mb-6">Official Partners & Sponsors</p>
    <div class="flex flex-wrap justify-center items-center gap-12 opacity-60 hover:opacity-100 transition duration-300">
        @forelse($partners as $partner)
            <div class="flex flex-col items-center">
                @if($partner->logo_url)
                    <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="Logo {{ $partner->name }}"
                         class="h-12 w-auto object-contain filter grayscale hover:grayscale-0 transition duration-300">
                @else
                    <span class="text-sm font-bold text-slate-700">{{ $partner->name }}</span>
                @endif
            </div>
        @empty
            <p class="text-slate-400 italic text-sm">AmikomEventHub belum memiliki partner resmi.</p>
        @endforelse
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-indigo-900 text-indigo-100 py-16 px-6 mt-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="space-y-4 md:col-span-1">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">AH</div>
                <span class="text-2xl font-bold text-white">AmikomEventHub</span>
            </div>
            <p class="max-w-xs text-indigo-300 text-sm">Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.</p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-6">Navigasi</h4>
            <ul class="space-y-3 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                <li><a href="{{ route('home') }}#events" class="hover:text-white transition">Semua Event</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
            <ul class="space-y-3 text-sm">
                <li>support@eventtiket.com</li>
                <li>+62 812 3456 7890</li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto pt-10 mt-10 border-t border-indigo-800 text-center text-indigo-400 text-sm">
        &copy; 2026 AmikomEventHub. Built with Laravel & Tailwind CSS.
    </div>
</footer>
@endsection