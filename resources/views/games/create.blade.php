@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Add New Game</h1>

<form 
    action="{{ route('games.store') }}" 
    method="POST" 
    enctype="multipart/form-data" 
    class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto"
>
    @csrf

    <!-- Title -->
    <div class="mb-5">
        <label class="block font-semibold mb-1">Title</label>
        <input type="text" name="title" 
               class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
    </div>

    <!-- Description -->
    <div class="mb-5">
        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" rows="4"
                  class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required></textarea>
    </div>

    <!-- Prices -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        <div>
            <label class="block font-semibold mb-1">Price</label>
            <input type="number" step="0.01" name="price"
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Rental Price/Day</label>
            <input type="number" step="0.01" name="rental_price"
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <div>
            <label class="block font-semibold mb-1">Stock</label>
            <input type="number" name="stock"
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

    </div>

    <!-- Images -->
    <div class="mb-6">
        <label class="block font-semibold mb-1">Images (Multiple allowed)</label>
        <input type="file" name="images[]" multiple accept="image/*"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg w-full font-semibold">
        Create Game
    </button>

</form>
@endsection
