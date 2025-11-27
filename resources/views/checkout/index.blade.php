@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Checkout</h1>

<table class="w-full bg-white p-4 rounded shadow">
    <tr class="border-b">
        <th class="p-2">Game</th>
        <th class="p-2">Qty</th>
        <th class="p-2">Total</th>
    </tr>

    @php $grandTotal = 0; @endphp

    @foreach($cart as $item)
        @php
            $title = $item['title'] ?? $item['name']; // supports cart + buy now
            $subtotal = $item['price'] * $item['quantity'];
            $grandTotal += $subtotal;
        @endphp

        <tr class="border-b">
            <td class="p-2">{{ $title }}</td>
            <td class="p-2">{{ $item['quantity'] }}</td>
            <td class="p-2">₹{{ $subtotal }}</td>
        </tr>
    @endforeach

</table>

<h2 class="text-xl font-bold mt-4">Grand Total: ₹{{ $grandTotal }}</h2>

<form action="{{ route('checkout.place') }}" method="POST" class="mt-4">
    @csrf

    {{-- SUPPORT BUY NOW --}}
    @if(isset($single) && $single)
        <input type="hidden" name="game_id" value="{{ array_key_first($cart) }}">
        <input type="hidden" name="quantity" value="{{ $cart[array_key_first($cart)]['quantity'] }}">
    @endif

    <button class="bg-green-600 text-white px-6 py-2 rounded">
        Confirm Purchase
    </button>
</form>
@endsection
