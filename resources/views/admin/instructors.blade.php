@extends('layouts.app')

@section('title', 'Persetujuan Instruktur')

@section('content')

<div class="max-w-5xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 font-sans">Persetujuan Instruktur</h1>
        <p class="text-gray-500 mt-1">Verifikasi atau nonaktifkan akun instruktur/panitia di bawah ini agar mereka dapat mengelola slot workshop.</p>
    </div>

    {{-- List Instruktur --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        @if($instructors->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <span class="text-4xl mb-2 block">👥</span>
                <p>Belum ada instruktur/panitia yang terdaftar.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Tanggal Registrasi</th>
                            <th class="px-6 py-4">Status Persetujuan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($instructors as $ins)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">
                                    {{ $ins->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $ins->email }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 font-medium">
                                    {{ $ins->created_at->format('d M Y • H:i') }} WIB
                                </td>
                                <td class="px-6 py-4">
                                    @if($ins->is_approved_instructor)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            Menunggu Persetujuan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.instructors.approve', $ins) }}" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-xl px-4 py-2 text-xs font-bold transition active:scale-95 shadow-sm
                                            {{ $ins->is_approved_instructor 
                                                ? 'bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100' 
                                                : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-100' }}">
                                            {{ $ins->is_approved_instructor ? 'Cabut Persetujuan' : 'Setujui Akun' }}
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
