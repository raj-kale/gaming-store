<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Show user's orders
    public function index()
    {
        $orders = auth()->user()->orders()->with('game')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Create order
    public function store(Request $request)
    {
        $game = Game::findOrFail($request->game_id);
        $quantity = $request->quantity ?? 1;

        // ❗ Prevent over-buying
        if ($game->stock < $quantity) {
            return back()->with('error', 'This game is out of stock.');
        }

        // Create the order
        Order::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'quantity' => $quantity,
            'total' => $game->price * $quantity,
        ]);

        // Decrease stock
        $game->decrement('stock', $quantity);

        return redirect()->route('orders.index')->with('success', 'Order placed!');
    }
}
