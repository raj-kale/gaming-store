@extends('layouts.app')

@section('content')
<div class="grid grid-cols-2 gap-8">
    <div>
        @if($game->hasMedia('images'))
            <img src="{{ $game->getFirstMediaUrl('images') }}" class="w-full rounded shadow mb-4">
            <div class="grid grid-cols-4 gap-2">
                @foreach($game->getMedia('images') as $media)
                    <img src="{{ $media->getUrl('thumb') }}" class="w-full rounded cursor-pointer">
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <h1 class="text-3xl font-bold mb-4">{{ $game->title }}</h1>
        <p class="text-gray-700 mb-6">{{ $game->description }}</p>
        
        <p class="text-2xl font-bold text-blue-600 mb-4">${{ $game->price }}</p>
        @if($game->rental_price)
            <p class="text-lg text-green-600 mb-4">Rent: ${{ $game->rental_price }}/day</p>
        @endif
        <p class="text-gray-600 mb-6">Stock: {{ $game->stock }}</p>

       @if(!auth()->user()->isAdmin())
 
        @auth
            <form action="{{ route('orders.store') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="game_id" value="{{ $game->id }}">
                <button class="w-full bg-green-600 text-white py-3 rounded">Buy Now</button>
            </form>
            @if($game->rental_price)
                <a href="{{ route('rentals.create', $game) }}" class="block text-center bg-yellow-600 text-white py-3 rounded">Rent This Game</a>
            @endif
        @endauth
         @endif
    </div>
</div>
@endsection