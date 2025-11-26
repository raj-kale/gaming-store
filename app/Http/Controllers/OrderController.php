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

        Order::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'quantity' => $quantity,
            'total' => $game->price * $quantity,
        ]);

        $game->decrement('stock', $quantity);

        return redirect()->route('orders.index')->with('success', 'Order placed!');
    }
}