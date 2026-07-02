<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pendapatan hanya dihitung dari transaksi yang statusnya Success
        $totalPendapatan = Transaction::where('status', 'Success')->sum('total_price');

        // Tiket terjual = jumlah transaksi yang berhasil (Success)
        $tiketTerjual = Transaction::where('status', 'Success')->count();

        // Event aktif = event yang tanggalnya belum lewat
        $eventAktif = Event::where('date', '>=', now())->count();

        // Pesanan pending
        $pesananPending = Transaction::where('status', 'Pending')->count();

        // 5 transaksi terakhir buat ditampilin di tabel
        $latestTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatan',
            'tiketTerjual',
            'eventAktif',
            'pesananPending',
            'latestTransactions'
        ));
    }
}