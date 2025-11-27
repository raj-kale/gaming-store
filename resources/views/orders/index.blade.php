@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Orders</h1>

@if($orders->isEmpty())
    <p class="text-gray-600">You have no orders yet.</p>
@else
    <div class="space-y-4">
        @foreach ($orders as $order)
            <div class="bg-white shadow p-4 rounded">
                <h2 class="font-bold text-xl">{{ $order->game->title }}</h2>
                <p>Quantity: {{ $order->quantity }}</p>
                <p>Total: Rs. {{ $order->total }}</p>
                <p class="text-gray-500 text-sm">Ordered on {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        @endforeach
    </div>
@endif
@endsection
