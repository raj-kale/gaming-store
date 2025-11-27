@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Your Cart</h1>

@if(empty($cart))
    <p>Your cart is empty.</p>
@else

<table class="w-full bg-white p-4 rounded shadow">
    <tr class="border-b">
        <th class="p-2">Image</th>
        <th class="p-2">Title</th>
        <th class="p-2">Qty</th>
        <th class="p-2">Price</th>
        <th class="p-2">Action</th>
    </tr>

    @foreach($cart as $id => $item)
    <tr class="border-b">
        <td class="p-2"><img src="{{ $item['image'] }}" class="h-12 rounded"></td>
        <td class="p-2">{{ $item['title'] }}</td>
        <td class="p-2">{{ $item['quantity'] }}</td>
        <td class="p-2">₹{{ $item['price'] }}</td>
        <td class="p-2">
            <form action="{{ route('cart.remove', $id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="text-red-500">Remove</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<div class="mt-4 flex justify-between">
    <form action="{{ route('cart.clear') }}" method="POST">
        @csrf @method('DELETE')
        <button class="bg-red-600 text-white px-4 py-2 rounded">Clear Cart</button>
    </form>

    <a href="{{ route('checkout') }}" class="bg-green-600 text-white px-4 py-2 rounded">
        Checkout
    </a>
</div>

@endif
@endsection
