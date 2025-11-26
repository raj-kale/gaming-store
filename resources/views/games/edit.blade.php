@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Edit Game</h1>

<form action="{{ route('games.update', $game) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow max-w-2xl">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block font-semibold mb-1">Title</label>
        <input type="text" name="title" value="{{ $game->title }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required>{{ $game->description }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block font-semibold mb-1">Price</label>
            <input type="number" step="0.01" name="price" value="{{ $game->price }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Rental Price/Day</label>
            <input type="number" step="0.01" name="rental_price" value="{{ $game->rental_price }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block font-semibold mb-1">Stock</label>
            <input type="number" name="stock" value="{{ $game->stock }}" class="w-full border rounded px-3 py-2" required>
        </div>
    </div>

    <!-- Upload new images -->
    <div class="mb-4">
        <label class="block font-semibold mb-1">Add New Images</label>
        <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded px-3 py-2">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
        Update Game
    </button>
</form>

<!-- Existing images displayed OUTSIDE the form -->
<h3 class="font-semibold mt-8 mb-2">Existing Images</h3>
<div class="grid grid-cols-4 gap-4 mb-4">
    @foreach($game->getMedia('images') as $media)
        <div class="relative">
            <img src="{{ $media->getUrl('thumb') }}" class="rounded shadow w-full h-24 object-cover">
            <form action="{{ route('games.image.delete', [$game->id, $media->id]) }}" 
                  method="POST" 
                  onsubmit="return confirm('Delete this image?')" 
                  class="absolute top-1 right-1">
                @csrf
                @method('DELETE')
                <button class="bg-red-600 text-white text-xs px-2 py-1 rounded">X</button>
            </form>
        </div>
    @endforeach
</div>

@endsection
