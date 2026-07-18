<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Organizer;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas
        $totalRevenue = Transaction::where('status', 'Success')->sum('total_price');

        // 2. Menghitung Berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = Transaction::where('status', 'Success')->count();

        // 3. Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = Event::where('date', '>=', now())->count();

        // 4. Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Expired)
        $pendingOrders = Transaction::where('status', 'Pending')->count();

        // 5. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }

    /**
     * Endpoint JSON untuk data grafik (dipanggil via fetch di Blade).
     * Query pakai Carbon/Collection (bukan DATE_FORMAT) supaya portable
     * antara SQLite (lokal) dan MySQL (hosting).
     */
    public function chartData()
    {
        // 6 bulan terakhir, format 'Y-m' (misal: 2026-02)
        $months = collect(range(5, 0))->map(
            fn ($i) => now()->subMonths($i)->format('Y-m')
        );

        // --- Pertumbuhan User per bulan ---
        $usersByMonth = User::where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get(['created_at'])
            ->groupBy(fn ($u) => $u->created_at->format('Y-m'))
            ->map->count();

        // --- Pertumbuhan Event per bulan ---
        $eventsByMonth = Event::where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get(['created_at'])
            ->groupBy(fn ($e) => $e->created_at->format('Y-m'))
            ->map->count();

        $userGrowth = $months->map(fn ($m) => $usersByMonth->get($m, 0))->values();
        $eventGrowth = $months->map(fn ($m) => $eventsByMonth->get($m, 0))->values();

        // --- Revenue per Organizer (top 10) ---
        $revenuePerOrganizer = Organizer::all()->map(function ($org) {
            return [
                'name' => $org->organization_name,
                'revenue' => Transaction::where('status', 'Success')
                    ->whereHas('event', fn ($q) => $q->where('organizer_id', $org->id))
                    ->sum('total_price'),
            ];
        })
        ->sortByDesc('revenue')
        ->take(10)
        ->values();

        return response()->json([
            'labels' => $months,
            'userGrowth' => $userGrowth,
            'eventGrowth' => $eventGrowth,
            'organizerNames' => $revenuePerOrganizer->pluck('name'),
            'organizerRevenue' => $revenuePerOrganizer->pluck('revenue'),
        ]);
    }
}