<?php

use App\Http\Controllers\ConcertController;
use App\Http\Controllers\OrganizerConcertController;
use App\Http\Controllers\TicketController;
use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'concerts' => Concert::where('concert_start', '>', Carbon::now())->orderBy('concert_start')->get(),
    ]);
})->middleware(['guest:web,organizer']);

Route::view('organizer/dashboard', 'organizers/dashboard')
    ->middleware(['auth:organizer', 'verified'])
    ->name('organizer.dashboard');

Route::controller(OrganizerConcertController::class)->middleware(['auth:organizer', 'verified'])
->group(function () {
    Route::get('organizer/concerts', 'index')
        ->name('organizer.concerts.index');

    Route::get('organizer/concerts/create/schedule', 'schedule')
        ->name('organizer.concerts.create.schedule');

    Route::get('organizer/concerts/create', 'create')
        ->name('organizer.concerts.create');

    Route::get('organizer/concerts/{concert}', 'show')
        ->name('organizer.concerts.show');
    
    Route::get('organizer/concerts/{concert}/print', 'print')
        ->name('organizer.sales.print');
});

Route::controller(ConcertController::class)
->middleware(['auth:web'])
->group(function () {
    Route::get('home', 'index')
        ->name('user.home');

    Route::get('concert/{concert}', 'show')
        ->name('user.concert.show');

    Route::get('concert/{concert}/order', 'order')
        ->name('user.concert.order');
});

Route::controller(TicketController::class)
->group(function () {
    Route::get('ticket/index', 'index')
        ->name('user.ticket.index');

    Route::get('ticket/{ticket}/delete', 'destroy')
        ->name('user.ticket.delete');
});



Route::view('profile', 'profile')
    ->middleware(['auth:web,organizer'])
    ->name('profile');

require __DIR__.'/auth.php';
