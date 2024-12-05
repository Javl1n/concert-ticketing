<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    protected function guard()
    {
        if (!auth('web')->check() && auth('organizer')->check()) {
            return redirect('organizer/dashboard');
        }
    }

    function index() {
        if (!auth('web')->check() && auth('organizer')->check()) {
            return redirect('organizer/dashboard');
        }

        $concerts = Concert::where('concert_start', '>', Carbon::now())
                        ->orderBy('concert_start')->get();

        return view('user.home', [
            'concerts' => $concerts
        ]);
    }

    function show(Concert $concert)
    {
        $this->guard();

        $carbon = new Carbon;


        return view('user.events.show', [
            'concert' => $concert,
            'carbon' => $carbon
        ]);
    }

    function order(Concert $concert)
    {
        $this->guard();

        return view('user.events.order', [
            'concert' => $concert,
        ]);
    }

    // function create($number) {
    //     return $number;
    // }
}
