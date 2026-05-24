@extends('layouts.app')

@section('title', 'Browse Workshops')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Browse Workshops</h1>
    <p class="text-gray-500 mt-1">Find and join workshops that interest you</p>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('home') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-8 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Workshop title..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="min-w-[150px]">
        <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
        <select name="category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="min-w-[130px]">
        <label class="block text-xs font-medium text-gray-600 mb-1">Price</label>
        <select name="price" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All</option>
            <option value="free"  {{ request('price') == 'free' ? 'selected' : '' }}>Free</option>
            <option value="paid"  {{ request('price') == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
        Filter
    </button>

    @if(request()->hasAny(['search', 'category', 'price']))
        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:underline py-2">Clear</a>
    @endif
</form>

{{-- Workshop Grid --}}
@if($workshops->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-lg">No workshops found.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($workshops as $workshop)
            <a href="{{ route('workshops.show', $workshop) }}"
                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition group">

                {{-- Banner --}}
                @if($workshop->banner_image)
                    <img src="{{ asset('storage/' . $workshop->banner_image) }}"
                        alt="{{ $workshop->title }}"
                        class="w-full h-40 object-cover group-hover:opacity-95 transition">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <span class="text-blue-400 text-4xl">📋</span>
                    </div>
                @endif

                <div class="p-4">
                    {{-- Category & Audience badges --}}
                    <div class="flex gap-2 mb-2 flex-wrap">
                        @if($workshop->category)
                            <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 rounded-full px-2 py-0.5">
                                {{ $workshop->category->name }}
                            </span>
                        @endif
                        <span class="text-xs bg-gray-50 text-gray-500 border border-gray-200 rounded-full px-2 py-0.5 capitalize">
                            {{ $workshop->audience }}
                        </span>
                    </div>

                    <h3 class="font-semibold text-gray-800 text-sm leading-snug mb-1 group-hover:text-blue-600 transition">
                        {{ $workshop->title }}
                    </h3>

                    <p class="text-xs text-gray-500 mb-3">
                        {{ $workshop->start_at->format('D, d M Y • H:i') }}
                    </p>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold {{ $workshop->isFree() ? 'text-green-600' : 'text-gray-800' }}">
                            {{ $workshop->isFree() ? 'Free' : 'Rp ' . number_format($workshop->price, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ $workshop->remainingSlots() }} slots left
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $workshops->withQueryString()->links() }}
    </div>
@endif

@endsection