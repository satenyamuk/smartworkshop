<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    public function dashboard()
    {
        // Hanya ambil workshop yang dikelola instruktur yang login
        $workshops = Workshop::where('instructor_id', Auth::id())
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('instructor.dashboard', compact('workshops'));
    }

    public function participants(Workshop $workshop)
    {
        // Proteksi agar instruktur hanya bisa melihat workshop miliknya sendiri
        if ($workshop->instructor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk mengelola workshop ini.');
        }

        // Muat semua tiket peserta baik aktif maupun dibatalkan
        $tickets = Ticket::where('workshop_id', $workshop->id)
            ->with(['class', 'order.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('instructor.participants', compact('workshop', 'tickets'));
    }

    public function updateCapacity(Request $request, Workshop $workshop)
    {
        if ($workshop->instructor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk mengelola workshop ini.');
        }

        $request->validate([
            'capacity' => 'required|integer|min:' . $workshop->tickets_sold,
        ], [
            'capacity.min' => 'Kapasitas tidak boleh lebih kecil dari jumlah tiket yang sudah terjual (' . $workshop->tickets_sold . ').',
        ]);

        $workshop->update([
            'capacity' => $request->capacity
        ]);

        return redirect()->back()->with('success', 'Kapasitas kuota workshop berhasil diperbarui!');
    }

    public function cancelTicket(Ticket $ticket)
    {
        $workshop = $ticket->workshop;

        if ($workshop->instructor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk memproses tiket ini.');
        }

        if ($ticket->status === 'cancelled') {
            return redirect()->back()->with('error', 'Tiket ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($ticket, $workshop) {
            // Batalkan tiket
            $ticket->update(['status' => 'cancelled']);
            
            // Kurangi jumlah tiket terjual di workshop agar slot bertambah
            if ($workshop->tickets_sold > 0) {
                $workshop->decrement('tickets_sold');
            }
        });

        return redirect()->back()->with('success', 'Tiket peserta dengan kode ' . $ticket->ticket_code . ' berhasil dibatalkan. Kuota slot dibebaskan.');
    }
}
