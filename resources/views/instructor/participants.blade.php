@extends('layouts.app')

@section('title', 'Kelola Peserta - ' . $workshop->title)

@section('content')

<div class="max-w-6xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('instructor.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $workshop->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Halaman panel untuk mengelola kapasitas kuota dan melacak pendaftaran peserta.</p>
        </div>
        <div class="flex gap-2">
            <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100 uppercase font-bold tracking-wider self-start capitalize">
                Audience: {{ $workshop->audience }}
            </span>
            <span class="text-xs bg-gray-50 text-gray-500 px-3 py-1 rounded-full border border-gray-200 uppercase font-bold tracking-wider self-start">
                {{ $workshop->isFree() ? 'Gratis' : 'Berbayar' }}
            </span>
        </div>
    </div>

    {{-- Stats Cards & Form Kuota --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        {{-- Stats Terjual --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Tiket Terjual</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $workshop->tickets_sold }}</h3>
            </div>
            <div class="text-3xl">🎫</div>
        </div>

        {{-- Stats Sisa Kuota --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Sisa Slot Tersedia</p>
                <h3 class="text-3xl font-extrabold text-green-600">{{ $workshop->remainingSlots() }}</h3>
            </div>
            <div class="text-3xl">✅</div>
        </div>

        {{-- Form Edit Kapasitas --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Ubah Kapasitas Kuota</p>
            <form method="POST" action="{{ route('instructor.workshops.capacity', $workshop) }}" class="flex gap-2">
                @csrf
                <input type="number" name="capacity" value="{{ old('capacity', $workshop->capacity) }}" min="{{ $workshop->tickets_sold }}" required
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 active:scale-95 transition shadow-sm shadow-blue-100 whitespace-nowrap">
                    Update
                </button>
            </form>
            @error('capacity')
                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- Daftar Peserta Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Daftar Peserta Terdaftar</h2>
            <span class="text-xs text-gray-400 font-medium">Total: {{ $tickets->count() }} Pendaftar</span>
        </div>

        @if($tickets->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <p class="text-lg">Belum ada peserta yang mendaftar di workshop ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                            <th class="px-6 py-4">Kode Tiket</th>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">NIS / NIP</th>
                            <th class="px-6 py-4">Kelas / Status</th>
                            <th class="px-6 py-4">Bukti Bayar</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-blue-600">
                                    {{ $ticket->ticket_code }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $ticket->participant_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $ticket->participant_email }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-600">
                                    {{ $ticket->participant_id_number }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $ticket->schoolClass ? $ticket->schoolClass->name : 'Guru' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->order->receipt_path)
                                        <a href="{{ asset('storage/' . $ticket->order->receipt_path) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline font-semibold">
                                            <span>📂</span> Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-600 border border-red-200">
                                            Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($ticket->status === 'active')
                                        <form method="POST" action="{{ route('instructor.tickets.cancel', $ticket) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tiket untuk peserta ini? Tindakan ini akan membebaskan 1 kuota slot.');"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-red-100 transition active:scale-95">
                                                Batalkan Tiket
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">Sudah Dibatalkan</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
