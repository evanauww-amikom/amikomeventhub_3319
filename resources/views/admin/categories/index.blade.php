@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manajemen Kategori</h1>

    <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="border p-2 rounded-lg w-full md:w-1/3">
        <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg text-sm flex items-center">Reset</a>
        @endif
    </form>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="mb-6">
        @csrf
        <div class="flex gap-2">
            <input type="text" name="name" placeholder="Nama Kategori Baru" class="border p-2 rounded-lg w-full" required>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">Tambah</button>
        </div>
    </form>

    <table class="w-full bg-white rounded-xl shadow-sm overflow-hidden">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-4 text-left">No</th>
                <th class="p-4 text-left">Nama Kategori</th>
                <th class="p-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $cat)
            <tr class="border-b">
                <td class="p-4">{{ $index + 1 }}</td>
                <td class="p-4">
                    <form action="{{ route('admin.categories.update', $cat->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $cat->name }}" class="border p-1 rounded">
                        <button type="submit" class="text-blue-600">Update</button>
                    </form>
                </td>
                <td class="p-4">
                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
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