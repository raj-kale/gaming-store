<?php
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    // Show user's rentals
    public function index()
    {
        $rentals = auth()->user()->rentals()->with('game')->latest()->get();
        return view('rentals.index', compact('rentals'));
    }

    // Show rental form
    public function create(Game $game)
    {
        return view('rentals.create', compact('game'));
    }

    // Create rental
    public function store(Request $request, Game $game)
    {
        $days = (int) $request->input('days', 7);
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($days);

        Rental::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total' => $game->rental_price * $days,
        ]);

        return redirect()->route('rentals.index')->with('success', 'Game rented!');
    }

    // Return rental
    public function return(Rental $rental)
    {
        $rental->update(['status' => 'returned']);
        return back()->with('success', 'Game returned!');
    }
}