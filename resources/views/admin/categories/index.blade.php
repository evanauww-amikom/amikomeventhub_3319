@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen Kategori</h1>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg mb-4">Tambah Kategori</button>

        <table class="w-full bg-white rounded-xl shadow-sm overflow-hidden">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Nama Kategori</th>
                    <th class="p-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-4">1</td>
                    <td class="p-4">Seminar</td>
                    <td class="p-4">
                        <button class="text-blue-600 mr-2">Edit</button>
                        <button class="text-red-600">Hapus</button>
                    </td>
                </tr>
                <tr>
                    <td class="p-4">2</td>
                    <td class="p-4">Konser</td>
                    <td class="p-4">
                        <button class="text-blue-600 mr-2">Edit</button>
                        <button class="text-red-600">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection