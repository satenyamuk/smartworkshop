@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-500 mt-1">Gunakan panel ini untuk mengontrol seluruh konten dan operasional platform SmartWorkshop.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Total Workshops --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Workshop</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['total_workshops'] }}</h3>
            </div>
            <div class="text-3xl bg-blue-50 p-2.5 rounded-xl">📋</div>
        </div>

        {{-- Total Tiket Terjual --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Tiket Terjual</p>
                <h3 class="text-3xl font-extrabold text-emerald-600">{{ $stats['total_tickets'] }}</h3>
            </div>
            <div class="text-3xl bg-emerald-50 p-2.5 rounded-xl">🎫</div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Revenue</p>
                <h3 class="text-xl font-extrabold text-gray-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            </div>
            <div class="text-3xl bg-amber-50 p-2.5 rounded-xl">💰</div>
        </div>

        {{-- Pending Approvals --}}
        <a href="{{ route('admin.instructors') }}" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex items-center justify-between hover:shadow-md transition group">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Persetujuan Instruktur</p>
                <h3 class="text-3xl font-extrabold {{ $stats['pending_teachers'] > 0 ? 'text-rose-600' : 'text-gray-800' }}">
                    {{ $stats['pending_teachers'] }}
                </h3>
            </div>
            <div class="text-3xl bg-rose-50 p-2.5 rounded-xl group-hover:scale-110 transition">👥</div>
        </a>

    </div>

    {{-- Menu Manajemen Utama --}}
    <h2 class="text-lg font-bold text-gray-800 mb-6">Navigasi Manajemen Utama</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Kelola Workshop --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Kelola Workshop</h3>
                <p class="text-xs text-gray-400 mb-6">Buat workshop baru, ubah rincian, tentukan instruktur, publish atau batalkan event.</p>
            </div>
            <a href="{{ route('admin.workshops.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-4 text-xs font-bold text-center transition">
                Masuk Modul
            </a>
        </div>

        {{-- Kelola Kategori --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Kategori Workshop</h3>
                <p class="text-xs text-gray-400 mb-6">Kelola kategori pengelompokan workshop untuk mempermudah pencarian oleh peserta.</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-4 text-xs font-bold text-center transition">
                Masuk Modul
            </a>
        </div>

        {{-- Kelola Kelas --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Manajemen Kelas</h3>
                <p class="text-xs text-gray-400 mb-6">Kelola data kelas sekolah yang nantinya digunakan untuk registrasi murid.</p>
            </div>
            <a href="{{ route('admin.classes.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-4 text-xs font-bold text-center transition">
                Masuk Modul
            </a>
        </div>

        {{-- Persetujuan Instruktur --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Daftar Instruktur</h3>
                <p class="text-xs text-gray-400 mb-6">Verifikasi pendaftaran instruktur (panitia) baru agar bisa ditugaskan di workshop.</p>
            </div>
            <a href="{{ route('admin.instructors') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-4 text-xs font-bold text-center transition">
                Masuk Modul
            </a>
        </div>

    </div>
</div>

@endsection
