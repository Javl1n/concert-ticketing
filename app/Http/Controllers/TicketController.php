<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    protected function guard()
    {
        if (!auth('web')->check() && auth('organizer')->check()) {
            return redirect('organizer/dashboard');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->guard();
        return view('user.tickets.index', [
            'tickets' => Ticket::where('user_id', auth()->user()->id)->get(),
            'carbon' => Carbon::now()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->update([
            'approved' => null,
            'bought_at' => null,
            'user_id' => null
        ]);

        return redirect(route('user.ticket.index'));
    }
}
