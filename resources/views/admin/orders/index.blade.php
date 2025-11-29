@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Order Transactions</h1>

        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search user or game"
                   class="border rounded px-2 py-1" />

            <select name="status" class="border rounded px-2 py-1">
                <option value="">All</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>

            <button class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
        </form>
    </div>

    @include('components.alerts')

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Game</th>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Purchased At</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $tx)
                    <tr>
                        <td class="px-4 py-2">{{ $tx->id }}</td>
                        <td class="px-4 py-2">{{ $tx->game?->title }}</td>
                        <td class="px-4 py-2">{{ $tx->user?->name }}</td>
                        <td class="px-4 py-2">{{ optional($tx->sold_at)->toDayDateTimeString() ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $tx->quantity }}</td>
                        <td class="px-4 py-2">₹{{ $tx->price }}</td>
                        <td class="px-4 py-2">{{ ucfirst($tx->status) }}</td>

                        <td class="px-4 py-2 flex gap-2">

                            @if($tx->status === 'paid')
                                <form method="POST" action="{{ route('admin.orders.cancel', $tx->id) }}"
                                      onsubmit="return confirm('Cancel this order?');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm bg-red-600 text-white px-3 py-1 rounded">
                                        Cancel
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.orders.refund', $tx->id) }}"
                                      onsubmit="return confirm('Refund this order?');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm bg-yellow-600 text-white px-3 py-1 rounded">
                                        Refund
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500 text-sm">—</span>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
