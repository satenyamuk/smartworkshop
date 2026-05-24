@extends('layouts.app')

@section('title', 'Pesan Tiket - ' . $workshop->title)

@section('content')

<div class="max-w-4xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('workshops.show', $workshop) }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
            &larr; Kembali ke detail workshop
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Ringkasan Workshop --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Ringkasan Workshop</h2>
                
                @if($workshop->banner_image)
                    <img src="{{ asset('storage/' . $workshop->banner_image) }}" alt="{{ $workshop->title }}" class="w-full h-32 object-cover rounded-xl mb-4">
                @else
                    <div class="w-full h-32 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl mb-4 flex items-center justify-center">
                        <span class="text-blue-400 text-3xl">📋</span>
                    </div>
                @endif

                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-2">{{ $workshop->title }}</h3>
                
                <div class="space-y-2 text-xs text-gray-500">
                    <p><span class="font-medium text-gray-700">Waktu:</span> {{ $workshop->start_at->format('D, d M Y • H:i') }}</p>
                    <p><span class="font-medium text-gray-700">Lokasi:</span> {{ $workshop->location ?? '-' }}</p>
                    <p><span class="font-medium text-gray-700">Audience:</span> <span class="capitalize">{{ $workshop->audience }}</span></p>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-700">Harga Tiket:</span>
                    <span class="text-base font-extrabold {{ $workshop->isFree() ? 'text-green-600' : 'text-gray-900' }}">
                        {{ $workshop->isFree() ? 'Gratis' : 'Rp ' . number_format($workshop->price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Checkout --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                <h1 class="text-xl font-bold text-gray-800 mb-2">Formulir Pemesanan Tiket</h1>
                <p class="text-sm text-gray-500 mb-6">Silakan lengkapi data diri Anda di bawah ini untuk memesan tiket.</p>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 text-sm rounded-xl p-4 mb-6 border border-red-100">
                        <ul class="list-disc list-inside space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('orders.store', $workshop) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Data Umum --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap Peserta</label>
                        <input type="text" name="participant_name" value="{{ old('participant_name', $user->name) }}" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Peserta</label>
                        <input type="email" name="participant_email" value="{{ old('participant_email', $user->email) }}" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                    {{-- Siswa --}}
                    @if($user->isStudent())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Induk Siswa (NIS)</label>
                                <input type="text" name="student_id" value="{{ old('student_id', $user->studentProfile?->student_id) }}" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                                <select name="class_id" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $user->studentProfile?->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    {{-- Guru --}}
                    @if($user->isTeacher())
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="teacher_id" value="{{ old('teacher_id', $user->teacherProfile?->teacher_id) }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>
                    @endif

                    {{-- Bukti Bayar --}}
                    @if(!$workshop->isFree())
                        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 mt-6">
                            <h3 class="text-sm font-bold text-blue-900 mb-2">Instruksi Pembayaran</h3>
                            <p class="text-xs text-blue-700 leading-relaxed mb-4">
                                Silakan lakukan transfer sebesar <span class="font-bold">Rp {{ number_format($workshop->price, 0, ',', '.') }}</span> ke rekening berikut:<br>
                                <span class="font-semibold">Bank Mandiri: 123-4567-890 a/n SmartWorkshop Panitia</span>.
                            </p>
                            
                            <div>
                                <label class="block text-sm font-semibold text-blue-900 mb-1">Upload Bukti Pembayaran (Gambar)</label>
                                <input type="file" name="receipt" required accept="image/*"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer transition">
                                <p class="text-[10px] text-blue-500 mt-1">Maksimum ukuran file: 2MB (.png, .jpg, .jpeg)</p>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" placeholder="Tulis catatan jika ada..."
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white rounded-xl py-3 text-sm font-bold hover:bg-blue-700 active:scale-[0.98] transition-all shadow-md shadow-blue-100">
                        {{ $workshop->isFree() ? 'Pesan Tiket Gratis Sekarang' : 'Kirim Pemesanan & Bukti Bayar' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
