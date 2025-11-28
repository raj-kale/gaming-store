@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">All Games</h1>

<!-- SEARCH + FILTER + SORT -->
<form method="GET" action="{{ route('home') }}" class="mb-6 bg-white p-4 rounded shadow grid grid-cols-4 gap-4">

    <!-- Search -->
    <div>
        <label class="text-sm font-semibold">Search</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search games..."
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- Price Min -->
    <div>
        <label class="text-sm font-semibold">Min Price</label>
        <input type="number" name="price_min" value="{{ request('price_min') }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- Price Max -->
    <div>
        <label class="text-sm font-semibold">Max Price</label>
        <input type="number" name="price_max" value="{{ request('price_max') }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- Sort -->
    <div>
        <label class="text-sm font-semibold">Sort</label>
        <select name="sort" class="w-full border rounded px-3 py-2">
            <option value="">Latest</option>
            <option value="price_low"  {{ request('sort')=='price_low' ? 'selected':'' }}>Price: Low → High</option>
            <option value="price_high" {{ request('sort')=='price_high' ? 'selected':'' }}>Price: High → Low</option>
        </select>
    </div>

    <!-- Submit button (full width) -->
    <div class="col-span-4">
        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Apply Filters
        </button>
    </div>
</form>


<div class="grid grid-cols-3 gap-6">
    
    @foreach($games as $game)
    
    <div class="bg-white rounded-lg shadow p-4">
        
        @if($game->hasMedia('images'))
    <a href="{{ route('games.show', $game) }}">
        <img 
            src="{{ $game->getFirstMediaUrl('images', 'thumb') }}" 
            class="w-full h-48 object-cover rounded mb-4 hover:opacity-90 transition"
        >
    </a>
@else
    <a href="{{ route('games.show', $game) }}">
        <div class="w-full h-48 bg-gray-300 rounded mb-4"></div>
    </a>
@endif

        
        <h3 class="font-bold text-lg">{{ $game->title }}</h3>
        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($game->description, 100) }}</p>
        <p class="text-blue-600 font-bold">Rs. {{ $game->price }}</p>
        
       <div class="mt-3 flex flex-wrap gap-2 justify-center text-xs">

    <!-- VIEW -->
    <!-- <a href="{{ route('games.show', $game) }}"
        class="px-3 py-1 bg-blue-600 text-white rounded flex items-center gap-1 hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path d="M2.5 12s4-7 9.5-7 9.5 7 9.5 7-4 7-9.5 7-9.5-7-9.5-7z"/><circle cx="12" cy="12" r="3"/></svg>
        View
    </a> -->

    @auth
        
    
        {{-- BUY --}}

@if($game->stock > 0)
    <form action="{{ route('checkout') }}" method="GET" class="m-0 p-0">
        <input type="hidden" name="game_id" value="{{ $game->id }}">
        <input type="hidden" name="quantity" value="1">

        <button type="submit"
            class="px-3 py-1 bg-green-600 text-white rounded flex items-center gap-1 hover:bg-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path d="M3 3h2l3 12h11l3-8H6"/></svg>
            Buy
        </button>

        <!-- askjdkajsdnkajsndkjasndkjnad -->
    </form>
@endif



        {{-- ADD TO CART --}}
<form action="{{ route('cart.add', $game) }}" method="POST">
    @csrf
    <button class="px-3 py-1 bg-blue-600 text-white rounded flex items-center gap-1 hover:bg-blue-700">
         Cart
    </button>
</form>


        {{-- RENT --}}
        @if($game->rental_price && $game->stock > 0)
            <a href="{{ route('rentals.create', $game) }}"
               class="px-3 py-1 bg-yellow-600 text-white rounded flex items-center gap-1 hover:bg-yellow-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                Rent
            </a>
        @endif

        {{-- ADMIN ONLY --}}
        @if(auth()->user()->isAdmin())
            <a href="{{ route('games.edit', $game) }}"
               class="px-3 py-1 bg-gray-600 text-white rounded flex items-center gap-1 hover:bg-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M11 5l7 7-7 7-7-7 7-7z"/></svg>
                Edit
            </a>

            <form action="{{ route('games.destroy', $game) }}" method="POST"
                  onsubmit="return confirm('Delete this game?');">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded flex items-center gap-1 hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path d="M3 6h18M9 6V4h6v2m-9 0v12h12V6"/></svg>
                    Del
                </button>
            </form>
        @endif

    @endauth
</div>


    </div>
    @endforeach
</div>
@endsection