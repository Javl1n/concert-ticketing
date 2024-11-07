<?php

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

Route::view('profile', 'profile')
    ->middleware(['auth:web,organizer'])
    ->name('profile');

require __DIR__.'/auth.php';
