@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Rental Transactions</h1>

        <form method="GET" action="{{ route('admin.rentals.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="search user or game" class="border rounded px-2 py-1" />
            <select name="status" class="border rounded px-2 py-1">
                <option value="">All</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Game</th>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Rented At</th>
                    <th class="px-4 py-2">Due At</th>
                    <th class="px-4 py-2">Returned At</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr class="{{ $tx->status === 'active' && $tx->due_at && $tx->due_at < now() ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-2 align-top break-words max-w-xs">{{ $tx->id }}</td>
                        <td class="px-4 py-2 align-top">{{ $tx->game?->title ?? '—' }}</td>
                        <td class="px-4 py-2 align-top">{{ $tx->user?->name ?? '—' }}</td>
                        <td class="px-4 py-2 align-top">{{ optional($tx->rented_at)->toDayDateTimeString() ?? '—' }}</td>
                        <td class="px-4 py-2 align-top">{{ optional($tx->due_at)->toDayDateTimeString() ?? '—' }}</td>
                        <td class="px-4 py-2 align-top">{{ optional($tx->returned_at)->toDayDateTimeString() ?? '—' }}</td>
                        <td class="px-4 py-2 align-top">{{ ucfirst($tx->status) }}</td>
                        <td class="px-4 py-2 align-top">
                            @if($tx->status === 'active')
                                <form method="POST" action="{{ route('admin.rentals.return', $tx->id) }}" onsubmit="return confirm('Mark this rental as returned?');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm bg-green-600 text-white px-3 py-1 rounded">Mark returned</button>
                                </form>
                            @else
                                <span class="text-gray-500 text-sm">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">No rentals found.</td>
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
