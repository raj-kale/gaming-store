@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Rentals</h1>

@if($rentals->isEmpty())
    <p class="text-gray-600">You have no rentals yet.</p>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        @foreach ($rentals as $rental)
            <div class="bg-white p-5 rounded-lg shadow">
                
                <!-- Game Image if available -->
                @if ($rental->game->hasMedia('images'))
                    <img src="{{ $rental->game->getFirstMediaUrl('images', 'thumb') }}"
                        class="w-full h-40 object-cover rounded mb-3">
                @endif

                <h2 class="text-xl font-bold mb-1">{{ $rental->game->title }}</h2>

                <p class="text-gray-700"><strong>Start:</strong> {{ $rental->start_date->format('M d, Y') }}</p>
                <p class="text-gray-700"><strong>End:</strong> {{ $rental->end_date->format('M d, Y') }}</p>

                <p class="mt-2 font-semibold text-blue-600">
                    Total: Rs. {{ $rental->total }}
                </p>

            </div>
        @endforeach

    </div>
@endif
@endsection
