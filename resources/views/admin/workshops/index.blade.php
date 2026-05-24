@extends('layouts.app')

@section('title', 'Kelola Workshop')

@section('content')

<div class="max-w-6xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Workshop</h1>
            <p class="text-gray-500 mt-1">Buat, terbitkan, batalkan, atau edit seluruh data workshop yang ada di platform.</p>
        </div>
        <a href="{{ route('admin.workshops.create') }}"
            class="inline-flex items-center gap-1 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-blue-700 transition active:scale-95 shadow-md shadow-blue-100 whitespace-nowrap">
            + Workshop Baru
        </a>
    </div>

    {{-- List Workshop --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        @if($workshops->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <span class="text-4xl mb-2 block">📋</span>
                <p>Belum ada workshop yang didaftarkan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                            <th class="px-6 py-4">Workshop</th>
                            <th class="px-6 py-4">Instruktur</th>
                            <th class="px-6 py-4">Harga / Target</th>
                            <th class="px-6 py-4">Slot Pendaftar</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($workshops as $ws)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 leading-snug">{{ $ws->title }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Mulai: {{ $ws->start_at->format('d M Y • H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium">
                                    {{ $ws->instructor->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 text-xs">
                                        {{ $ws->isFree() ? 'Gratis' : 'Rp ' . number_format($ws->price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mt-0.5 capitalize">{{ $ws->audience }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-xs">
                                    <span class="text-gray-800 font-bold">{{ $ws->tickets_sold }}</span>
                                    <span class="text-gray-400">/</span>
                                    <span class="text-gray-500">{{ $ws->capacity }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ws->status === 'published')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-200">
                                            Published
                                        </span>
                                    @elseif($ws->status === 'draft')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                                            Draft
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-600 border border-red-200">
                                            Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <a href="{{ route('admin.workshops.edit', $ws) }}"
                                        class="bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 px-3 py-1.5 rounded-xl text-xs font-bold transition active:scale-95">
                                        Edit
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.workshops.destroy', $ws) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus workshop ini?');"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-3 py-1.5 rounded-xl text-xs font-bold transition active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
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
