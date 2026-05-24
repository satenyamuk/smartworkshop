@extends('layouts.app')

@section('title', 'Dashboard Panitia')

@section('content')

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 font-sans">Dashboard Panitia</h1>
        <p class="text-gray-500 mt-1">Kelola kuota, tiket, dan daftar peserta untuk workshop Anda</p>
    </div>

    @if($workshops->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
            <span class="text-5xl mb-4 block">📋</span>
            <h2 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Workshop</h2>
            <p class="text-gray-400 text-sm mb-4">Anda belum ditugaskan sebagai instruktur di workshop manapun.</p>
            <p class="text-xs text-gray-400">Silakan hubungi Administrator untuk membuat workshop baru dan menunjuk Anda sebagai instruktur.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                            <th class="px-6 py-4">Workshop</th>
                            <th class="px-6 py-4">Kategori & Target</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Terjual / Kapasitas</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($workshops as $ws)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-gray-800 leading-snug">{{ $ws->title }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">ID: #{{ $ws->id }} • {{ $ws->isFree() ? 'Gratis' : 'Rp ' . number_format($ws->price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1">
                                        @if($ws->category)
                                            <span class="self-start text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100 uppercase font-bold tracking-wider">
                                                {{ $ws->category->name }}
                                            </span>
                                        @endif
                                        <span class="self-start text-[10px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded border border-gray-200 capitalize font-medium">
                                            Audience: {{ $ws->audience }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-xs text-gray-500 font-medium">
                                    {{ $ws->start_at->format('D, d M Y') }}<br>
                                    {{ $ws->start_at->format('H:i') }} WIB
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-800">{{ $ws->tickets_sold }}</span>
                                        <span class="text-gray-400">/</span>
                                        <span class="text-gray-500">{{ $ws->capacity }}</span>
                                        @if($ws->isFull())
                                            <span class="text-[9px] bg-red-50 text-red-500 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Penuh</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Progress Bar Kuota --}}
                                    <div class="w-24 bg-gray-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, ($ws->tickets_sold / max(1, $ws->capacity)) * 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('instructor.workshops.participants', $ws) }}"
                                        class="inline-flex items-center gap-1 bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition active:scale-95 shadow-sm shadow-blue-100">
                                        Kelola Peserta & Kuota
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@endsection
