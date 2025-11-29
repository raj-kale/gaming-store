<?php
use App\Http\Controllers\GameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

Route::middleware(['auth','is_admin'])->prefix('admin')->group(function () {
    // show admin rentals tracking page
    Route::get('rentals', [ReportController::class, 'rentalsIndex'])->name('admin.rentals.index');

    // mark rental as returned (PATCH)
    Route::patch('rentals/{id}/return', [ReportController::class, 'markReturned'])->name('admin.rentals.return');
});


use App\Http\Controllers\RentController;

Route::middleware(['auth'])->group(function () {
    Route::post('/games/{game}/rent', [RentController::class, 'rent'])->name('games.rent');
});


// temporary debug route — remove after debugging
Route::get('/whoami', function () {
    return response()->json([
        'auth_check' => auth()->check(),
        'user'       => auth()->user(),
    ]);
})->middleware('web'); // ensure web session middleware is applied

Route::middleware(['auth'])->group(function(){
     Route::get('/admin/orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])
        ->name('admin.orders.index');

    Route::patch('/admin/orders/{transaction}/cancel', [\App\Http\Controllers\Admin\OrderAdminController::class, 'cancel'])
        ->name('admin.orders.cancel');

    Route::patch('/admin/orders/{transaction}/refund', [\App\Http\Controllers\Admin\OrderAdminController::class, 'refund'])
        ->name('admin.orders.refund');
});