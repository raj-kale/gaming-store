<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('type', 'sale')
            ->with(['user', 'game']);

        // SEARCH
        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })->orWhereHas('game', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");
            });
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('transactions'));
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->status !== 'paid') {
            return back()->with('error', 'Order cannot be cancelled.');
        }

        $transaction->status = 'cancelled';
        $transaction->save();

        // Restore stock
        $transaction->game->increment('stock', $transaction->quantity);

        return back()->with('success', 'Order cancelled and stock restored.');
    }

    public function refund(Transaction $transaction)
    {
        if ($transaction->status !== 'paid') {
            return back()->with('error', 'Order cannot be refunded.');
        }

        $transaction->status = 'refunded';
        $transaction->save();

        return back()->with('success', 'Order refunded.');
    }
}
