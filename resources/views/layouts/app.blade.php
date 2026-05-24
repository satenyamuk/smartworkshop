<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartWorkshop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

{{-- Navbar --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-blue-600 font-bold text-lg">SmartWorkshop</a>

        <div class="flex items-center gap-4 text-sm">
            @auth
                <span class="text-gray-500">Hi, {{ Auth::user()->name }}</span>

                @if(Auth::user()->isAdmin())
                    <a href="/admin" class="text-gray-700 hover:text-blue-600">Admin</a>
                @endif

                @if(Auth::user()->isInstructor())
                    <a href="/instructor" class="text-gray-700 hover:text-blue-600">My Workshops</a>
                @endif

<a href="/tickets" class="text-gray-700 hover:text-blue-600">My Tickets</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500 hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition">Register</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Flash Message --}}
@if(session('success'))
    <div class="max-w-6xl mx-auto px-4 mt-4">
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-6xl mx-auto px-4 mt-4">
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    </div>
@endif

{{-- Page Content --}}
<main class="flex-1 max-w-6xl mx-auto px-4 py-8 w-full">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 text-center text-xs text-gray-400 py-4">
    SmartWorkshop &copy; {{ date('Y') }}
</footer>

</body>
</html>