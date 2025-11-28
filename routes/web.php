<?php
use App\Http\Controllers\GameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('home');
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::resource('games', GameController::class);
Route::delete('games/{game}/image/{media}', [GameController::class, 'deleteImage'])->name('games.image.delete');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{game}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{game}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [OrderController::class, 'checkout'])->middleware('auth')->name('checkout');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->middleware('auth')->name('checkout.place');


require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    
    Route::get('rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('rentals/create/{game}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('rentals/{game}', [RentalController::class, 'store'])->name('rentals.store');
    Route::post('rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
});