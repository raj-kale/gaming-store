<?php
namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class GameController extends Controller
{
    // Show all games (public)
    public function index(Request $request)
    {
        
        $query = Game::query();

    // SEARCH
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    //price filter MIN
    if($request->filled('price_min')){
        $query->where('price','>=', $request->price_min);
    }

    //price filter MAX
    if($request->filled('price_max')){
        $query->where('price','<=', $request->price_max);
    }

    //sort
    if($request->sort==='price_low'){
        $query->orderBy('price','asc');
    } elseif($request->sort==='price_high'){
        $query->orderBy('price','desc');
    } else{
        $query->latest();
    }

    //fetch
    $games=$query->get();
    return view('games.index', compact('games'));


    }

    // Admin: Show create form
    public function create()
    {
        return view('games.create');
    }

    // Admin: Store new game with images
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'rental_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|max:2048',
        ]);

        $game = Game::create($validated);

        // SPATIE MEDIA: Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $game->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('games.index')->with('success', 'Game created!');
    }

    // Show single game
    public function show(Game $game)
    {
        return view('games.show', compact('game'));
    }

    // Admin: Show edit form
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    // Admin: Update game
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'rental_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|max:2048',
        ]);

        $game->update($validated);

        // SPATIE MEDIA: Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $game->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('games.index')->with('success', 'Game updated!');
    }

    // Admin: Delete game
  
public function destroy(Game $game)
{
    // Safety: ask for a DB backup in logs (admin should backup before destructive ops)
    // Check active transactions first
    $hasActive = Transaction::where('game_id', $game->id)
        ->where('status', 'active')
        ->exists();

    if ($hasActive) {
        return redirect()->route('games.index')
            ->with('error', 'Cannot delete game: there are active rentals/sales for this game. Wait until they are returned/completed.');
    }

    // No active transactions: delete completed/cancelled transactions, then delete the game
    try {
        DB::transaction(function () use ($game) {
            // Delete historical (non-active) transactions referencing this game.
            Transaction::where('game_id', $game->id)
                ->whereIn('status', ['completed', 'cancelled'])
                ->delete();

            // Now safe to delete the game (foreign key will not block)
            $game->delete(); // Spatie handles media deletion if configured
        });

        return redirect()->route('games.index')->with('success', 'Game and its completed transaction history have been deleted.');
    } catch (\Throwable $e) {
        // Log the exception or show friendly message
        \Log::error('Failed to delete game and its transactions', ['game_id' => $game->id, 'error' => $e->getMessage()]);
        return redirect()->route('games.index')->with('error', 'Failed to delete game. See logs for details.');
    }
}


    // Delete specific image
    public function deleteImage($gameId, $mediaId)
    {
        $game = Game::findOrFail($gameId);
        $game->deleteMedia($mediaId);
        return back()->with('success', 'Image deleted!');
    }
}