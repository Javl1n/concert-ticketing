<?php

use App\Http\Controllers\ConcertController;
use App\Http\Controllers\OrganizerConcertController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->middleware(['guest:web,organizer']);

Route::get('home', function () {
    if (!auth('web')->check() && auth('organizer')->check()) {
        return redirect('organizer/dashboard');
    }
    return view('user.home');
})
    ->name('user.home');

Route::view('organizer/dashboard', 'organizers/dashboard')
    ->middleware(['auth:organizer', 'verified'])
    ->name('organizer.dashboard');

Route::controller(OrganizerConcertController::class)->middleware(['auth:organizer', 'verified'])->group(function () {
    Route::get('organizer/concerts', 'index')
        ->name('organizer.concerts.index');

    Route::get('organizer/concerts/create/schedule', 'schedule')
        ->name('organizer.concerts.create.schedule');

    Route::get('organizer/concerts/create', 'create')
        ->name('organizer.concerts.create');
});



Route::view('profile', 'profile')
    ->middleware(['auth:web,organizer'])
    ->name('profile');

require __DIR__.'/auth.php';
