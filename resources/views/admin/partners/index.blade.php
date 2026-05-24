@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manajemen Partner</h1>

    <form action="{{ route('admin.partners.index') }}" method="GET" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama partner..." class="border p-2 rounded-lg w-full md:w-1/3">
        <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.partners.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg text-sm flex items-center">Reset</a>
        @endif
    </form>
    <form action="{{ route('admin.partners.store') }}" method="POST" class="mb-6 bg-white p-4 rounded-xl shadow-sm">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" name="name" placeholder="Nama Partner" class="border p-2 rounded-lg" required>
            <input type="text" name="logo_url" placeholder="URL Logo (contoh: https://link-gambar.com/logo.png)" class="border p-2 rounded-lg" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg">Tambah Partner</button>
    </form>

    <table class="w-full bg-white rounded-xl shadow-sm overflow-hidden">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-4 text-left">No</th>
                <th class="p-4 text-left">Logo</th>
                <th class="p-4 text-left">Nama Partner</th>
                <th class="p-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partners as $index => $partner)
            <tr class="border-b">
                <td class="p-4">{{ $index + 1 }}</td>
                <td class="p-4">
                    <img src="{{ $partner->logo_url }}" alt="Logo" class="h-10 w-auto object-contain bg-gray-50 rounded">
                </td>
                <td class="p-4">
                    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $partner->name }}" class="border p-1 rounded w-32">
                        <input type="text" name="logo_url" value="{{ $partner->logo_url }}" class="border p-1 rounded w-48 text-xs">
                        <button type="submit" class="text-blue-600 text-sm">Update</button>
                    </form>
                </td>
                <td class="p-4">
                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection