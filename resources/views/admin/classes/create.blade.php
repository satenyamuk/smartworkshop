@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')

<div class="max-w-xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke daftar kelas
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
        <h1 class="text-xl font-bold text-gray-800 mb-2">Tambah Kelas Baru</h1>
        <p class="text-sm text-gray-500 mb-6">Daftarkan kelas sekolah baru ke dalam sistem.</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-xl p-4 mb-6 border border-red-100">
                <ul class="list-disc list-inside space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kelas X - IPA 1, Kelas XI - IPS 2" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tingkat / Level (Opsional)</label>
                <input type="text" name="grade_level" value="{{ old('grade_level') }}" placeholder="Contoh: 10, 11, 12, atau Menengah"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white rounded-xl py-2.5 text-sm font-bold hover:bg-blue-700 active:scale-[0.98] transition-all shadow-md shadow-blue-100">
                Simpan Kelas Baru
            </button>
        </form>
    </div>
</div>

@endsection
