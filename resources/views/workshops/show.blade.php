@extends('layouts.app')

@section('title', $workshop->title)

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Banner --}}
    @if($workshop->banner_image)
        <img src="{{ asset('storage/' . $workshop->banner_image) }}"
            alt="{{ $workshop->title }}"
            class="w-full h-56 object-cover rounded-2xl mb-6">
    @else
        <div class="w-full h-56 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl mb-6 flex items-center justify-center">
            <span class="text-blue-300 text-6xl">📋</span>
        </div>
    @endif

    {{-- Badges --}}
    <div class="flex gap-2 mb-4 flex-wrap">
        @if($workshop->category)
            <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 rounded-full px-3 py-1">
                {{ $workshop->category->name }}
            </span>
        @endif
        <span class="text-xs bg-gray-50 text-gray-500 border border-gray-200 rounded-full px-3 py-1 capitalize">
            {{ $workshop->audience }} only
        </span>
        @if($workshop->isFull())
            <span class="text-xs bg-red-50 text-red-500 border border-red-100 rounded-full px-3 py-1">Full</span>
        @endif
    </div>

    {{-- Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $workshop->title }}</h1>

    {{-- Info Grid --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-gray-400 text-xs mb-0.5">Instructor</p>
            <p class="text-gray-700 font-medium">{{ $workshop->instructor->name }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs mb-0.5">Date & Time</p>
            <p class="text-gray-700 font-medium">{{ $workshop->start_at->format('D, d M Y • H:i') }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs mb-0.5">Location</p>
            <p class="text-gray-700 font-medium">{{ $workshop->location ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs mb-0.5">Slots Available</p>
            <p class="text-gray-700 font-medium">{{ $workshop->remainingSlots() }} / {{ $workshop->capacity }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs mb-0.5">Price</p>
            <p class="font-bold {{ $workshop->isFree() ? 'text-green-600' : 'text-gray-800' }}">
                {{ $workshop->isFree() ? 'Free' : 'Rp ' . number_format($workshop->price, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Description --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-2">About this workshop</h2>
        <p class="text-gray-600 text-sm leading-relaxed">{{ $workshop->description ?? 'No description provided.' }}</p>
    </div>

    {{-- Buy Button --}}
    @auth
        @if(!$workshop->isFull())
            <a href="{{ route('orders.create', $workshop) }}"
                class="block w-full text-center bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 transition">
                Get Ticket
            </a>
        @else
            <button disabled class="block w-full text-center bg-gray-200 text-gray-400 rounded-xl py-3 font-semibold cursor-not-allowed">
                Workshop Full
            </button>
        @endif
    @else
        <a href="{{ route('login') }}"
            class="block w-full text-center bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 transition">
            Login to Get Ticket
        </a>
    @endauth

</div>

@endsection