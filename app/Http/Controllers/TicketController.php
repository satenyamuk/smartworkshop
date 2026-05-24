<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        // Ambil tiket yang terhubung dengan pesanan milik user saat ini
        $tickets = Ticket::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['workshop', 'class', 'order'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('tickets.index', compact('tickets'));
    }
}
