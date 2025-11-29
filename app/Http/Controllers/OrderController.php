<?php

namespace App\Http\Controllers;
use App\Models\Transaction;

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

    public function checkout(Request $request)
{
    // If coming from "Buy Now"
    if ($request->has('game_id')) {
        $game = Game::findOrFail($request->game_id);
        $quantity = $request->quantity ?? 1;

        return view('checkout.index', [
            'cart' => [
                $game->id => [
                    'name' => $game->title,
                    'price' => $game->price,
                    'quantity' => $quantity,
                    'image' => $game->getFirstMediaUrl('images', 'thumb')
                ]
            ],
            'single' => true, // used later
        ]);
    }

    // Otherwise checkout the CART
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')
                         ->with('error', 'Cart is Empty!');
    }

    return view('checkout.index', [
        'cart' => $cart,
        'single' => false,
    ]);
}


    public function placeOrder(Request $request)
{
    // ============================
    // 1️⃣ SINGLE CHECKOUT (Buy Now)
    // ============================
    if ($request->has('game_id')) {
        $game = Game::findOrFail($request->game_id);
        $quantity = $request->quantity ?? 1;

        if ($game->stock < $quantity) {
            return back()->with('error', $game->title . ' is out of stock.');
        }

        // Create order table entry
        $order = Order::create([
            'user_id'  => auth()->id(),
            'game_id'  => $game->id,
            'quantity' => $quantity,
            'total'    => $game->price * $quantity,
            'status'   => 'paid',
        ]);

        // Create transaction entry for admin tracking
        Transaction::create([
            'user_id'  => auth()->id(),
            'game_id'  => $game->id,
            'admin_id' => null,
            'type'     => 'sale',
            'status'   => 'completed',
            'price'    => $game->price * $quantity,
            'sold_at'  => now(),
            'notes'    => null,
        ]);

        $game->decrement('stock', $quantity);

        return redirect()->route('orders.index')
                         ->with('success', 'Order placed successfully!');
    }

    // =======================
    // 2️⃣ CART CHECKOUT
    // =======================
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Cart is Empty!');
    }

    foreach ($cart as $id => $item) {

        $game = Game::find($id);

        if ($game->stock < $item['quantity']) {
            return back()->with('error', $game->title . ' is out of stock.');
        }

        // Create order
        Order::create([
            'user_id'  => auth()->id(),
            'game_id'  => $id,
            'quantity' => $item['quantity'],
            'total'    => $item['price'] * $item['quantity'],
            'status'   => 'paid',
        ]);

        // Create transaction for admin tracking
        Transaction::create([
            'user_id'  => auth()->id(),
            'game_id'  => $id,
            'admin_id' => null,
            'type'     => 'sale',
            'status'   => 'completed',
            'price'    => $item['price'] * $item['quantity'],
            'sold_at'  => now(),
            'notes'    => null,
        ]);

        $game->decrement('stock', $item['quantity']);
    }

    session()->forget('cart');

    return redirect()->route('orders.index')
                     ->with('success', 'Order Placed Successfully');
}


}
