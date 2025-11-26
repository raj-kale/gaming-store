@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">All Games</h1>

<div class="grid grid-cols-3 gap-6">
    @foreach($games as $game)
    <div class="bg-white rounded-lg shadow p-4">
        @if($game->hasMedia('images'))
            <img src="{{ $game->getFirstMediaUrl('images', 'thumb') }}" class="w-full h-48 object-cover rounded mb-4">
        @else
            <div class="w-full h-48 bg-gray-300 rounded mb-4"></div>
        @endif
        
        <h3 class="font-bold text-lg">{{ $game->title }}</h3>
        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($game->description, 100) }}</p>
        <p class="text-blue-600 font-bold">${{ $game->price }}</p>
        
        <div class="mt-4 space-y-2">

    <!-- VIEW button -->
    <a href="{{ route('games.show', $game) }}" 
       class="block text-center bg-blue-600 text-white py-2 rounded">
        View
    </a>

    @auth

    {{-- BUY only if stock > 0 --}}
    @if($game->stock > 0)
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="game_id" value="{{ $game->id }}">
            <button class="w-full bg-green-600 text-white py-2 rounded">
                Buy Now
            </button>
        </form>
    @else
        <div class="w-full bg-red-600 text-white py-2 rounded text-center">
            SOLD OUT
        </div>
    @endif

    {{-- RENT only if stock > 0 AND rental price exists --}}
    @if($game->rental_price && $game->stock > 0)
        <a href="{{ route('rentals.create', $game) }}" 
           class="block text-center bg-yellow-600 text-white py-2 rounded">
            Rent
        </a>
    @endif

    {{-- ADMIN CONTROLS --}}
    @if(auth()->user()->isAdmin())
        <a href="{{ route('games.edit', $game) }}" 
           class="block text-center bg-gray-600 text-white py-2 rounded">
            Edit
        </a>

        <form action="{{ route('games.destroy', $game) }}" 
              method="POST"
              onsubmit="return confirm('Delete this game?');">
            @csrf
            @method('DELETE')
            <button class="w-full bg-red-600 text-white py-2 rounded">
                Delete
            </button>
        </form>
    @endif

@endauth


</div>

    </div>
    @endforeach
</div>
@endsection