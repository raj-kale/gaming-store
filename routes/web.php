<?php
use App\Http\Controllers\GameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', [GameController::class, 'index'])->name('home');
Route::resource('games', GameController::class);
Route::delete('games/{game}/image/{media}', [GameController::class, 'deleteImage'])->name('games.image.delete');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    
    Route::get('rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('rentals/create/{game}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('rentals/{game}', [RentalController::class, 'store'])->name('rentals.store');
    Route::post('rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
});

Route::middleware(['auth','is_admin'])->prefix('admin')->group(function () {
    // show admin rentals tracking page
    Route::get('rentals', [ReportController::class, 'rentalsIndex'])->name('admin.rentals.index');

    // mark rental as returned (PATCH)
    Route::patch('rentals/{id}/return', [ReportController::class, 'markReturned'])->name('admin.rentals.return');
});


// temporary debug route — remove after debugging
Route::get('/whoami', function () {
    return response()->json([
        'auth_check' => auth()->check(),
        'user'       => auth()->user(),
    ]);
})->middleware('web'); // ensure web session middleware is applied
