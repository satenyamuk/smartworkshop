<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\Workshop;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create(Workshop $workshop)
    {
        // Cek kuota penuh
        if ($workshop->isFull()) {
            return redirect()->route('workshops.show', $workshop)->with('error', 'Maaf, kuota workshop ini sudah penuh.');
        }

        $user = Auth::user();
        
        // Proteksi kecocokan audiens (hanya student untuk student, dsb)
        if ($workshop->audience === 'student' && !$user->isStudent()) {
            abort(403, 'Workshop ini hanya terbuka untuk siswa.');
        }
        if ($workshop->audience === 'teacher' && !$user->isTeacher()) {
            abort(403, 'Workshop ini hanya terbuka untuk guru.');
        }

        $classes = SchoolClass::orderBy('name')->get();
        
        return view('orders.create', compact('workshop', 'user', 'classes'));
    }

    public function store(Request $request, Workshop $workshop)
    {
        if ($workshop->isFull()) {
            return redirect()->route('workshops.show', $workshop)->with('error', 'Maaf, kuota workshop ini sudah penuh.');
        }

        $user = Auth::user();

        // Validasi input
        $rules = [
            'participant_name'  => 'required|string|max:150',
            'participant_email' => 'required|email|max:150',
            'notes'             => 'nullable|string',
        ];

        if ($user->isStudent()) {
            $rules['student_id'] = 'required|string|max:100';
            $rules['class_id']   = 'required|exists:classes,id';
        } else {
            $rules['teacher_id'] = 'required|string|max:100';
        }

        // Jika workshop berbayar, bukti transfer wajib diupload
        if (!$workshop->isFree()) {
            $rules['receipt'] = 'required|image|max:2048'; // maks 2MB
        }

        $request->validate($rules);

        // Upload bukti pembayaran jika ada
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        // DB Transaction untuk menjamin konsistensi data pemesanan
        DB::transaction(function () use ($request, $workshop, $user, $receiptPath) {
            // 1. Buat Order
            $order = Order::create([
                'user_id'      => $user->id,
                'workshop_id'  => $workshop->id,
                'quantity'     => 1,
                'total_amount' => $workshop->price,
                'receipt_path' => $receiptPath,
                'notes'        => $request->notes,
            ]);

            // 2. Buat Tiket
            $ticketCode = 'SW-' . strtoupper(Str::random(8));
            while (Ticket::where('ticket_code', $ticketCode)->exists()) {
                $ticketCode = 'SW-' . strtoupper(Str::random(8));
            }

            Ticket::create([
                'order_id'              => $order->id,
                'workshop_id'           => $workshop->id,
                'ticket_code'           => $ticketCode,
                'participant_type'      => $user->role, // student / teacher
                'participant_name'      => $request->participant_name,
                'participant_id_number' => $user->isStudent() ? $request->student_id : $request->teacher_id,
                'participant_email'     => $request->participant_email,
                'class_id'              => $user->isStudent() ? $request->class_id : null,
                'status'                => 'active',
            ]);

            // 3. Update Kuota Workshop
            $workshop->increment('tickets_sold');
        });

        return redirect()->route('tickets.index')->with('success', 'Tiket workshop berhasil dipesan!');
    }
}
