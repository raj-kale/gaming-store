<?php
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    // Show user's rentals
   // Show user's active rentals (hide returned/completed/cancelled)
public function index()
{
    $rentals = auth()->user()
        ->transactions()            // user's transactions relation
        ->where('type', 'rental')   // only rentals
        ->where('status', 'active') // only active ones (hides completed/cancelled)
        ->with('game')
        ->latest()
        ->get();

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
        $request->validate([
            'days' => 'nullable|integer|min:1|max:365',
        ]);

        // don't allow admins to rent (per your requirement)
        if (auth()->user()->is_admin ?? false) {
            abort(403, 'Admins cannot rent games.');
        }

        $days = (int) $request->input('days', 7);
        $startDate = Carbon::now();
        $dueDate = $startDate->copy()->addDays($days);

        try {
            DB::transaction(function () use ($game, $startDate, $dueDate, $days) {
                // create Transaction row
                Transaction::create([
                    'user_id'   => auth()->id(),
                    'game_id'   => $game->id,
                    'admin_id'  => null,
                    'type'      => 'rental',
                    'status'    => 'active',
                    'price'     => ($game->rental_price ?? 0) * $days,
                    'rented_at' => $startDate,
                    'due_at'    => $dueDate,

                ]);

                // optional: decrement game stock if you track stock
                if (isset($game->stock)) {
                    $game->decrement('stock', 1);
                }
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not rent game: '.$e->getMessage());
        }

        return redirect()->route('rentals.index')->with('success', 'Game rented!');
    }

    // Return rental (mark transaction returned)
    public function return(Transaction $rental)
    {
        // ensure this is a rental and owned by the user or user is admin
        $user = auth()->user();
        if (! $user) return redirect()->route('login');

        // admin can mark returned via admin page; users can return own rentals
        if (!($user->is_admin ?? false) && $rental->user_id !== $user->id) {
            abort(403);
        }

        if ($rental->type !== 'rental') {
            return back()->with('error', 'Not a rental');
        }

        if ($rental->status !== 'active') {
            return back()->with('error', 'Rental not active');
        }

        try {
            DB::transaction(function () use ($rental) {
                $rental->returned_at = now();
                $rental->status = 'completed';
                $rental->admin_id = auth()->id();
                $rental->save();

                // return stock if tracked
                $game = $rental->game;
                if ($game && isset($game->stock)) {
                    $game->increment('stock', 1);
                }
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not mark returned: '.$e->getMessage());
        }

        return back()->with('success', 'Game returned.');
    }
}
