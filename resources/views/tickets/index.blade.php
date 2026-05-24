@extends('layouts.app')

@section('title', 'Tiket Saya')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tiket Saya</h1>
        <p class="text-gray-500 mt-1">Daftar seluruh tiket workshop yang telah Anda pesan</p>
    </div>

    @if($tickets->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
            <span class="text-5xl mb-4 block">🎫</span>
            <h2 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Tiket</h2>
            <p class="text-gray-400 text-sm mb-6">Anda belum memesan tiket workshop apapun saat ini.</p>
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                Cari Workshop Menarik
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($tickets as $ticket)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm flex flex-col md:flex-row transition hover:shadow-md">
                    
                    {{-- Detail Workshop & Peserta --}}
                    <div class="flex-1 p-6 md:p-8 flex flex-col justify-between">
                        <div>
                            {{-- Badge Kategori & Status --}}
                            <div class="flex items-center gap-2 mb-3">
                                @if($ticket->workshop->category)
                                    <span class="text-[10px] uppercase font-bold tracking-wider bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">
                                        {{ $ticket->workshop->category->name }}
                                    </span>
                                @endif
                                <span class="text-[10px] uppercase font-bold tracking-wider bg-gray-50 text-gray-500 px-2 py-0.5 rounded border border-gray-200 capitalize">
                                    Target: {{ $ticket->workshop->audience }}
                                </span>
                            </div>

                            <h2 class="text-xl font-bold text-gray-800 leading-snug mb-2 hover:text-blue-600 transition">
                                <a href="{{ route('workshops.show', $ticket->workshop) }}">{{ $ticket->workshop->title }}</a>
                            </h2>

                            {{-- Waktu & Lokasi --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-1.5 gap-x-4 text-xs text-gray-500 mb-6 mt-4">
                                <div class="flex items-center gap-1.5">
                                    <span>📅</span>
                                    <span>{{ $ticket->workshop->start_at->format('D, d M Y • H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>📍</span>
                                    <span>{{ $ticket->workshop->location ?? 'Online' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>👤</span>
                                    <span>Instruktur: {{ $ticket->workshop->instructor->name }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Peserta --}}
                        <div class="border-t border-gray-100 pt-4 mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <p class="text-gray-400 mb-0.5 font-medium">Nama Peserta</p>
                                <p class="text-gray-700 font-semibold">{{ $ticket->participant_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5 font-medium">Nomor Identitas (NIS/NIP)</p>
                                <p class="text-gray-700 font-semibold">{{ $ticket->participant_id_number }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5 font-medium">Kelas / Status</p>
                                <p class="text-gray-700 font-semibold">
                                    {{ $ticket->schoolClass ? $ticket->schoolClass->name : 'Guru / Staf' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Kanan / Potongan Tiket (Tear-off section) --}}
                    <div class="border-t md:border-t-0 md:border-l border-dashed border-gray-200 bg-gray-50 p-6 md:p-8 w-full md:w-64 flex flex-col justify-between items-center text-center">
                        <div class="w-full">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-1">Kode Tiket</span>
                            <span class="text-lg font-mono font-extrabold text-blue-600 block bg-blue-50 border border-blue-100 rounded-lg py-1 px-3 mb-4 select-all">
                                {{ $ticket->ticket_code }}
                            </span>
                        </div>

                        {{-- QR Code Mock (Styled with CSS) --}}
                        <div class="my-3 flex justify-center">
                            <div class="p-2 bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col items-center gap-1 select-none">
                                <div class="grid grid-cols-4 gap-0.5 w-12 h-12 opacity-80">
                                    <div class="bg-gray-800"></div><div class="bg-transparent"></div><div class="bg-gray-800"></div><div class="bg-gray-800"></div>
                                    <div class="bg-transparent"></div><div class="bg-gray-800"></div><div class="bg-transparent"></div><div class="bg-gray-800"></div>
                                    <div class="bg-gray-800"></div><div class="bg-transparent"></div><div class="bg-gray-800"></div><div class="bg-transparent"></div>
                                    <div class="bg-gray-800"></div><div class="bg-gray-800"></div><div class="bg-transparent"></div><div class="bg-gray-800"></div>
                                </div>
                                <span class="text-[8px] text-gray-400 font-mono tracking-tighter">SCAN ME</span>
                            </div>
                        </div>

                        <div class="w-full mt-4">
                            @if($ticket->status === 'active')
                                <span class="inline-flex w-full justify-center items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Tiket Aktif
                                </span>
                            @else
                                <span class="inline-flex w-full justify-center items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
