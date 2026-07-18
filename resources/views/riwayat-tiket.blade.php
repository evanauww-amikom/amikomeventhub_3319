@extends('layouts.app')
@section('content')
<main class="max-w-4xl mx-auto px-6 py-12">

    <h1 class="text-3xl font-black text-slate-900 mb-2">Tiket Saya</h1>
    <p class="text-slate-500 mb-10">Riwayat pembelian tiket dan ulasan event yang sudah kamu ikuti.</p>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 px-5 py-4 rounded-2xl mb-6 font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 text-rose-600 px-5 py-4 rounded-2xl mb-6 font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    @forelse($transactions as $transaction)
        @php $event = $transaction->event; @endphp
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 mb-6">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-1">{{ $transaction->order_id }}</p>
                    <h2 class="text-xl font-black text-slate-900">{{ $event->title ?? 'Event tidak ditemukan' }}</h2>
                    @if($event)
                        <p class="text-slate-500 text-sm mt-1">
                            {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y - H:i') }} WIB &middot; {{ $event->location }}
                        </p>
                    @endif
                </div>
                <a href="{{ route('ticket', $transaction->id) }}"
                    class="shrink-0 px-5 py-3 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-sm hover:bg-indigo-100 transition text-center">
                    Lihat E-Ticket
                </a>
            </div>

            @if($event)
                @if(!$event->hasEnded())
                    <div class="border-t border-slate-100 pt-4 mt-4">
                        <p class="text-sm text-slate-400 font-medium">Ulasan bisa diisi setelah event selesai.</p>
                    </div>
                @elseif(in_array($event->id, $reviewedEventIds))
                    <div class="border-t border-slate-100 pt-4 mt-4 flex items-center gap-2 text-emerald-600 font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Terima kasih, kamu sudah memberi ulasan untuk event ini.
                    </div>
                @else
                    <div class="border-t border-slate-100 pt-6 mt-4">
                        <p class="font-bold text-slate-800 mb-3">Beri ulasan untuk event ini</p>
                        <form action="{{ route('reviews.store', $event->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="flex items-center gap-2" x-data="{ rating: 0 }">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="peer hidden" required>
                                        <svg class="w-8 h-8 text-slate-200 peer-checked:text-amber-400 hover:text-amber-300 transition"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.367 2.445c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.983 9.384c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.957z"></path>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            <textarea name="comment" rows="3" placeholder="Ceritakan pengalamanmu (opsional)"
                                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition"></textarea>
                            <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    @empty
        <div class="text-center py-20">
            <p class="text-slate-400 font-medium">Kamu belum punya tiket yang berhasil dibeli.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 text-indigo-600 font-bold hover:underline">Cari event sekarang</a>
        </div>
    @endforelse

</main>
@endsection