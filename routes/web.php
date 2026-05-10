<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationEmailController;




  Route::prefix('manager')->group(function () {
    Route::get('/dashboard', function () {
        return view('manager.manager'); 
    })->name('manager.dashboard');

    Route::get('/open-tables', function () {
        return view('manager.manager'); 
    })->name('manager.open-tables');

    Route::get('/inventory', function () {
        return view('manager.manager'); 
    })->name('manager.inventory');
});

Route::prefix('waiter')->group(function () {
    
    // Dashboard (waiter.blade.php)
    Route::get('/dashboard', function () {
        return view('waiter.waiter'); 
    })->name('waiter.dashboard');

    // Menu/POS (menu.blade.php)
    Route::get('/menu', function () {
        return view('waiter.menu'); 
    })->name('waiter.menu');

    // Reservation Hub (reservation.blade.php)
    // Ang URL nito ay: 127.0.0.1:8000/waiter/reservation
    Route::get('/reservation', function () {
        return view('waiter.reservation'); 
    })->name('waiter.reservation');

});


Route::prefix('order')->group(function () {
    Route::get('/qrcodes', function () {
        return view('customer.qr-code');
    })->name('order.qrcodes');

    Route::get('/qrcodes{table}', function (string $table) {
        $tableNumber = intval($table);
        if ($tableNumber < 1 || $tableNumber > 15) {
            abort(404);
        }
        return redirect()->route('order.booking-choice');
    })->where('table', '[1-9]|1[0-5]')->name('order.qrcodes.single');

    Route::get('/select/{table?}', function (?int $table = null) {
        return view('customer.select-tables', ['table' => $table]);
    })->where('table', '[1-9]|1[0-5]')->name('order.select-tables');

    Route::get('/choice', function () {
        return view('customer.booking-choice');
    })->name('order.booking-choice');

    Route::get('/setup{table}', function (string $table) {
        $tableNumber = intval($table);
        if ($tableNumber < 1 || $tableNumber > 15) {
            abort(404);
        }

        return redirect()->route('order.menu', ['table' => $tableNumber]);
    })->where('table', '[1-9]|1[0-5]')->name('order.setup');

    Route::get('/menu', function () {
        return view('customer.customer-menu');
    })->name('order.menu');

    Route::get('/cart', function () {
        return view('customer.cart');
    })->name('order.cart');

    Route::get('/checkout', function () {
        return view('customer.checkout');
    })->name('order.checkout');
});

Route::get('/order', function () {
    return redirect()->route('order.booking-choice');
});

Route::post('/book', function (Request $request) {
    // Handle form submission - redirect to waiter
    return redirect()->route('waiter.reservation');
})->name('customer.book.post');

Route::get('/book', function () {
    return view('reservation.book'); 
})->name('customer.book');

Route::post('/reservation/confirm-email', [ReservationEmailController::class, 'confirmEmail'])
    ->name('reservation.confirm.email');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () { return view('login.login'); })->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('waiter.dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    })->name('login.post');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});