<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConcertController extends Controller
{
    function index() {
        return view('organizers.events.index');
    }

    function create($number) {
        return $number;
    }
}
