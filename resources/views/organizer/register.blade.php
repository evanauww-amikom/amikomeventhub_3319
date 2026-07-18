@extends('layouts.app')

@section('content')
<section class="max-w-2xl mx-auto px-6 py-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm px-8 py-10 md:px-14 md:py-14">
        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
            Jadi Mitra Penyelenggara
        </span>
        <h1 class="text-2xl md:text-3xl font-black leading-tight text-slate-900 mb-2">
            Daftarkan Kepanitiaan / HIMA Kamu
        </h1>
        <p class="text-slate-500 leading-relaxed mb-8">
            Kelola event, jual tiket, dan pantau pendapatanmu sendiri di AmikomEventHub.
        </p>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 text-rose-600 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('organizer.register') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Penanggung Jawab</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>
            </div>

            <hr class="border-slate-100">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Organisasi / Kepanitiaan</label>
                <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                       placeholder="Contoh: HIMA Sistem Informasi"
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi Singkat (opsional)</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Logo Organisasi (opsional)</label>
                <input type="file" name="logo" accept="image/*"
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl">
            </div>

            <button type="submit"
                    class="w-full px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                Daftar Sekarang
            </button>
        </form>
    </div>
</section>
@endsection