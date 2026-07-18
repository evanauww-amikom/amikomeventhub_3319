<?php
// app/Http/Controllers/OrganizerEventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction; // Tambahkan import Model Transaction
use Carbon\Carbon;          // Tambahkan import Carbon untuk mencatat waktu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrganizerEventController extends Controller
{
    // Helper: pastikan event yang diakses beneran milik organizer yang login
    private function authorizeOwnership(Event $event): void
    {
        $organizerId = Auth::user()->organizer->id;

        if ($event->organizer_id !== $organizerId) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }

    public function index()
    {
        $organizer = Auth::user()->organizer;
        $events = Event::where('organizer_id', $organizer->id)
            ->with('category')
            ->latest()
            ->get();

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'date'        => 'required|date',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data['organizer_id'] = Auth::user()->organizer->id;

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        Event::create($data);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        $this->authorizeOwnership($event);

        $categories = Category::all();
        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwnership($event);

        $data = $request->validate([
            'category_id' => 'required',
            'title'       => 'required',
            'description' => 'required',
            'date'        => 'required',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOwnership($event);

        if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dihapus.');
    }

    // =========================================================================
    // FITUR PILIHAN (FASE 5): MENAMPILKAN HALAMAN SCANNER DI SISI ORGANIZER
    // =========================================================================
    public function scanner()
    {
        return view('organizer.scanner');
    }

    // =========================================================================
    // FITUR PILIHAN (FASE 5): VERIFIKASI QR CODE VIA AJAX
    // =========================================================================
    public function verifyQr(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string'
        ]);

        // 1. Ambil data transaksi yang sukses berdasarkan order_id hasil scan
        $transaction = Transaction::where('order_id', $request->order_id)
            ->where('status', 'Success')
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak valid atau pembayaran belum lunas!'
            ], 404);
        }

        // 2. Multi-Tenant Protection: Cek kepemilikan event menggunakan helper bawaan kamu
        $organizerId = Auth::user()->organizer->id;
        if ($transaction->event->organizer_id !== $organizerId) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket ini milik event kepanitiaan lain!'
            ], 403);
        }

        // 3. Anti-Double Entry Check
        if ($transaction->check_in_status === 'Used') {
            return response()->json([
                'success' => false,
                'message' => 'PERINGATAN: Tiket sudah terpakai pada ' . 
                             Carbon::parse($transaction->checked_in_at)->format('d M, H:i')
            ], 400);
        }

        // 4. Update status tiket jika lolos semua verifikasi
        $transaction->update([
            'check_in_status' => 'Used',
            'checked_in_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil! Selamat datang, ' . $transaction->customer_name,
            'customer_name' => $transaction->customer_name,
            'event_title' => $transaction->event->title
        ]);
    }
}