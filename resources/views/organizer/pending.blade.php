@extends('layouts.app')

@section('content')
<section class="max-w-xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm px-8 py-14">
        @if($organizer && $organizer->status === 'rejected')
            <span class="inline-block px-4 py-1.5 bg-rose-100 text-rose-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                Ditolak
            </span>
            <h1 class="text-2xl font-black text-slate-900 mb-2">Pendaftaran Ditolak</h1>
            <p class="text-slate-500">Maaf, pengajuan organisasi kamu belum bisa disetujui. Silakan hubungi admin untuk info lebih lanjut.</p>
        @else
            <span class="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                Menunggu Verifikasi
            </span>
            <h1 class="text-2xl font-black text-slate-900 mb-2">Akun Kamu Sedang Direview</h1>
            <p class="text-slate-500">
                Terima kasih sudah mendaftar sebagai organizer, {{ $organizer->organization_name ?? '' }}!
                Tim kami akan memverifikasi datamu dalam 1x24 jam.
            </p>
        @endif
    </div>
</section>
@endsection