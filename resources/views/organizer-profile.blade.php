@extends('layouts.app')
@section('content')
<main class="max-w-5xl mx-auto px-6 py-12">

    {{-- Header Organizer --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 md:p-10 mb-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
        <div class="w-24 h-24 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-black text-3xl shrink-0 overflow-hidden">
            @if($organizer->logo_path && \Storage::disk('public')->exists($organizer->logo_path))
                <img src="{{ asset('storage/'.$organizer->logo_path) }}" alt="{{ $organizer->organization_name }}" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($organizer->organization_name, 0, 2)) }}
            @endif
        </div>
        <div class="flex-1">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900">{{ $organizer->organization_name }}</h1>
            <p class="text-slate-500 mt-2 max-w-xl">{{ $organizer->description ?? 'Penyelenggara event di AmikomEventHub.' }}</p>

            <div class="flex items-center justify-center md:justify-start gap-2 mt-4">
                <div class="flex text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($organizer->averageRating()) ? '' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.367 2.445c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.983 9.384c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                    @endfor
                </div>
                <span class="font-bold text-slate-700">{{ $organizer->averageRating() }}</span>
                <span class="text-slate-400 text-sm">({{ $organizer->totalReviews() }} ulasan)</span>
            </div>
        </div>
    </div>

    {{-- Daftar Event --}}
    @if($organizer->events->count())
        <h2 class="text-xl font-black text-slate-900 mb-4">Event dari {{ $organizer->organization_name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            @foreach($organizer->events as $ev)
                <a href="{{ route('events.show', $ev->id) }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:shadow-md transition">
                    <p class="font-bold text-slate-800">{{ $ev->title }}</p>
                    <p class="text-sm text-slate-400 mt-1">{{ \Carbon\Carbon::parse($ev->date)->translatedFormat('d F Y') }}</p>
                </a>
            @endforeach
        </div>
    @endif

    {{-- List Testimoni --}}
    <h2 class="text-xl font-black text-slate-900 mb-4">Ulasan Peserta</h2>
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white rounded-2xl border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-bold text-slate-800">{{ $review->user->name ?? 'Pengguna' }}</p>
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? '' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.367 2.445c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.983 9.384c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.957z"></path>
                            </svg>
                        @endfor
                    </div>
                </div>
                <p class="text-xs text-slate-400 mb-3">untuk event <span class="font-bold">{{ $review->event->title ?? '-' }}</span></p>
                @if($review->comment)
                    <p class="text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="text-slate-400 font-medium py-10 text-center">Belum ada ulasan untuk penyelenggara ini.</p>
        @endforelse
    </div>

</main>
@endsection