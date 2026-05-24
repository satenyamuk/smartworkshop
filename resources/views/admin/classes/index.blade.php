@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')

<div class="max-w-4xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 font-sans">Daftar Kelas Sekolah</h1>
            <p class="text-gray-500 mt-1">Kelola data kelas yang digunakan sebagai opsi saat pendaftaran siswa baru.</p>
        </div>
        <a href="{{ route('admin.classes.create') }}"
            class="inline-flex items-center gap-1 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-blue-700 transition active:scale-95 shadow-md shadow-blue-100 whitespace-nowrap">
            + Kelas Baru
        </a>
    </div>

    {{-- List Kelas --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        @if($classes->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <span class="text-4xl mb-2 block">🏫</span>
                <p>Belum ada kelas yang didaftarkan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4">Tingkat / Keterangan</th>
                            <th class="px-6 py-4">Jumlah Siswa Terdaftar</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($classes as $class)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">
                                    {{ $class->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium">
                                    {{ $class->grade_level ?? 'Tidak ada tingkat' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-600">
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded border border-emerald-100 font-bold">
                                        {{ $class->student_profiles_count }} Siswa
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <a href="{{ route('admin.classes.edit', $class) }}"
                                        class="bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 px-3 py-1.5 rounded-xl text-xs font-bold transition active:scale-95">
                                        Edit
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.classes.destroy', $class) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');"
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
