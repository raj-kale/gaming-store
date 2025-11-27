@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Rent {{ $game->title }}</h1>

<form 
    action="{{ route('rentals.store', $game->id) }}" 
    method="POST" 
    class="bg-white p-6 rounded-lg shadow max-w-xl mx-auto"
>
    @csrf

    <div class="mb-4">
        <label class="block font-semibold mb-1">Days to Rent</label>
        <input 
            type="number" 
            name="days" 
            value="7" 
            min="1"
            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
            required
        >
    </div>

    <div class="mb-4">
        <p class="text-gray-700">
            <strong>Price per day:</strong> Rs. {{ $game->rental_price }}
        </p>
        <p class="text-gray-700">
            <strong>Game:</strong> {{ $game->title }}
        </p>
    </div>

    <button 
        type="submit" 
        class="bg-blue-600 text-white px-6 py-2 rounded-lg w-full hover:bg-blue-700"
    >
        Rent Game
    </button>
</form>
@endsection
