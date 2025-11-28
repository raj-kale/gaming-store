<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Game;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{

    /**
     * Show paginated list of rental transactions (active & completed) with filters.
     */
   public function rentalsIndex(Request $request)
{
    // debug: simple unconditional query
    $transactions = \App\Models\Transaction::with(['game','user','admin'])
        ->where('type', 'rental')
        ->orderBy('rented_at', 'desc')
        ->paginate(25);

    return view('admin.rentals', compact('transactions'));
}


    /**
     * Mark a rental transaction as returned (admin action).
     */
    public function markReturned($id)
    {
        try {
            $tx = DB::transaction(function () use ($id) {
                $tx = Transaction::lockForUpdate()->findOrFail($id);

                if ($tx->type !== 'rental') {
                    throw new \Exception('Transaction is not a rental.');
                }

                if ($tx->status !== 'active') {
                    throw new \Exception('Transaction is not active.');
                }

                // mark returned
                $tx->returned_at = now();
                $tx->status = 'completed';
                $tx->admin_id = auth()->id();
                $tx->save();

                // restore stock (if your Game has stock)
                $game = Game::find($tx->game_id);
                if ($game && isset($game->stock)) {
                    $game->increment('stock', 1);
                }

                return $tx;
            });

            return redirect()->route('admin.rentals.index')->with('success', 'Rental marked returned.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.rentals.index')->with('error', 'Could not mark returned: ' . $e->getMessage());
        }
    }
}
