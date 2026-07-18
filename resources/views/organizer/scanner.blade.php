@extends('layouts.organizer') {{-- Sesuaikan nama main layout organizer-mu --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-2xl shadow-xl overflow-hidden p-6 border border-gray-100">
        <h2 class="text-2xl font-black text-gray-900 text-center mb-2">QR Code Check-in</h2>
        <p class="text-sm text-gray-500 text-center mb-6">Arahkan kamera ke kode QR E-Ticket peserta</p>

        <!-- Kamera View Scanner -->
        <div class="overflow-hidden rounded-xl bg-gray-50 border border-gray-200" style="width: 100%;" id="reader"></div>

        <!-- Box Notifikasi / Respon Hasil Scan -->
        <div id="result-box" class="mt-6 p-4 rounded-xl hidden text-center font-bold text-sm">
            <p id="result-message"></p>
        </div>
    </div>
</div>

<!-- Script HTML5 QR Code & AJAX Axios/Fetch -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
        fps: 10, 
        qrbox: { width: 250, height: 250 } 
    });
    
    html5QrcodeScanner.render(onScanSuccess);

    let isProcessing = false;

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return; // Mencegah double trigger saat request sedang berjalan
        isProcessing = true;

        const resultBox = document.getElementById('result-box');
        const resultMessage = document.getElementById('result-message');

        resultBox.className = "mt-6 p-4 rounded-xl text-center font-bold text-sm bg-yellow-100 text-yellow-800";
        resultMessage.innerText = "Memverifikasi tiket...";
        resultBox.classList.remove('hidden');

        // Mengirim data order_id ke backend via Fetch API
        fetch("{{ route('organizer.scanner.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ order_id: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultBox.className = "mt-6 p-4 rounded-xl text-center font-bold text-sm bg-green-100 text-green-800";
                resultMessage.innerHTML = `🎉 ${data.message}<br><span class="text-xs font-normal">${data.event_title}</span>`;
            } else {
                resultBox.className = "mt-6 p-4 rounded-xl text-center font-bold text-sm bg-red-100 text-red-800";
                resultMessage.innerText = `❌ ${data.message}`;
            }
            
            // Jeda 3 detik sebelum memperbolehkan kamera memindai tiket berikutnya
            setTimeout(() => { isProcessing = false; }, 3000);
        })
        .catch(error => {
            resultBox.className = "mt-6 p-4 rounded-xl text-center font-bold text-sm bg-red-100 text-red-800";
            resultMessage.innerText = "❌ Terjadi kesalahan koneksi sistem.";
            setTimeout(() => { isProcessing = false; }, 3000);
        });
    }
</script>
@endsection