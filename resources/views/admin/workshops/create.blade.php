@extends('layouts.app')

@section('title', 'Buat Workshop Baru')

@section('content')

<div class="max-w-3xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.workshops.index') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke daftar workshop
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
        <h1 class="text-xl font-bold text-gray-800 mb-2">Buat Workshop Baru</h1>
        <p class="text-sm text-gray-500 mb-6">Silakan lengkapi data formulir di bawah ini untuk membuat event workshop baru.</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-xl p-4 mb-6 border border-red-100">
                <ul class="list-disc list-inside space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.workshops.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Judul Workshop --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Workshop</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Belajar Laravel 11 dari Dasar" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            {{-- Instruktur & Kategori --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instruktur / Panitia</label>
                    <select name="instructor_id" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">Pilih Instruktur</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}" {{ old('instructor_id') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                    <select name="category_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">Pilih Kategori (Opsional)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Workshop</label>
                <textarea name="description" rows="5" placeholder="Tuliskan silabus, materi, prasyarat, dan deskripsi detail workshop di sini..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('description') }}</textarea>
            </div>

            {{-- Banner Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Banner</label>
                <input type="file" name="banner" accept="image/*"
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer transition">
                <p class="text-[10px] text-gray-400 mt-1">Maksimum ukuran gambar banner: 2MB (.png, .jpg, .jpeg)</p>
            </div>

            {{-- Waktu & Lokasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu Mulai</label>
                    <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu Selesai (Opsional)</label>
                    <input type="datetime-local" name="end_at" value="{{ old('end_at') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Lab Komputer 1 / Zoom"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            {{-- Kapasitas & Harga & Target --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kapasitas (Kuota Slot)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 30) }}" min="1" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Tiket (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <p class="text-[10px] text-gray-400 mt-1">Isi 0 jika workshop gratis.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Audience Target</label>
                    <select name="audience" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="public" {{ old('audience') == 'public' ? 'selected' : '' }}>Publik</option>
                        <option value="student" {{ old('audience') == 'student' ? 'selected' : '' }}>Siswa Saja</option>
                        <option value="teacher" {{ old('audience') == 'teacher' ? 'selected' : '' }}>Guru Saja</option>
                    </select>
                </div>
            </div>

            {{-- Status Penerbitan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status Penerbitan</label>
                <select name="status" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Tampilkan ke Publik)</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white rounded-xl py-3 text-sm font-bold hover:bg-blue-700 active:scale-[0.98] transition-all shadow-md shadow-blue-100">
                Simpan & Daftarkan Workshop Baru
            </button>
        </form>
    </div>
</div>

@endsection
