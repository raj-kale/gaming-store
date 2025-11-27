@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">All Games</h1>

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
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="game_id" value="{{ $game->id }}">
                <button class="px-3 py-1 bg-green-600 text-white rounded flex items-center gap-1 hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path d="M3 3h2l3 12h11l3-8H6"/></svg>
                    Buy
                </button>
            </form>
        @else
            <div class="px-3 py-1 bg-red-600 text-white rounded flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                Sold
            </div>
        @endif

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