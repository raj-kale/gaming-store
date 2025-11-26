<?php
use App\Http\Controllers\GameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

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