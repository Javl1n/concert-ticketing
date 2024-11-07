<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function redirect() {
        if(! auth('organizer')->check()) {
            return redirect('dashboard');
        }
    }
}
