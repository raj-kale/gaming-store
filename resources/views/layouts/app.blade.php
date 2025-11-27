<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Gaming Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

            <a href="/" class="text-2xl font-bold flex items-center gap-2">
                🎮 <span>Mini Gaming Store</span>
            </a>

            <div class="flex items-center gap-6 text-gray-700 font-medium">
                @auth
                    
                    <a href="{{ route('orders.index') }}" class="hover:text-blue-600">My Orders</a>
                    <a href="{{ route('rentals.index') }}" class="hover:text-blue-600">My Rentals</a>
                    
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('games.create') }}" class="hover:text-blue-600">Add Game</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="hover:text-red-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-600">Login</a>
                     <a href="{{ route('register') }}" class="ml-4">Register</a>
                @endauth
            </div>

        </div>
    </nav>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-6">
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md shadow">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- PAGE CONTENT -->
    <main class="max-w-7xl mx-auto px-4 py-10">
        @yield('content')
    </main>

</body>
</html>
