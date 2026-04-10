<!DOCTYPE html>
<html>
<head>
    <title>Halaman Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200 text-center max-w-sm w-full">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Profil Mahasiswa</h1>
        <div class="text-slate-600 mb-6 space-y-1">
            <p><span class="font-semibold">Nama:</span> Evan Aubin Wibowo</p>
            <p><span class="font-semibold">NIM:</span> 24.12.3319</p>
            <p><span class="font-semibold">Kelas:</span> Sistem Informasi 4</p>
        </div>
        
        <div class="grid grid-cols-2 gap-3">
            <a href="/" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 hover:shadow-md transition duration-300">
                Home
            </a>
            <a href="/katalog" class="inline-block bg-amber-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-amber-700 hover:shadow-md transition duration-300">
                Katalog
            </a>
            <a href="/kontak" class="inline-block bg-rose-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-rose-700 hover:shadow-md transition duration-300">
                Kontak
            </a>
            <a href="/bantuan" class="inline-block bg-cyan-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-cyan-700 hover:shadow-md transition duration-300">
                Bantuan
            </a>
        </div>
    </div>
</body>
</html>