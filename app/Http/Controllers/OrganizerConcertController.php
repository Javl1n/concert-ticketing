<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class OrganizerConcertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizer = auth()->user();
        // dd($organizer->concerts->first()->Image);
        return view('organizers.events.index', [
            'concerts' => $organizer->concerts()->orderBy('reservation_start')->get(),
        ]);
    }

    public function schedule()
    {
        return view('organizers.events.schedule');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
        $firstDay = Carbon::create($request->firstDay);
        $lastDay = Carbon::create($request->lastDay);


        return view('organizers.events.create', [
            'firstDay' => $firstDay,
            'lastDay' => $lastDay
        ]);
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
    public function show(Concert $concert)
    {
        $carbon = new Carbon;

        return view('organizers.events.show', [
            'concert' => $concert,
            'carbon' => $carbon
        ]);
    }

    public function print(Concert $concert)
    {
        $total = 0.00;
        foreach ($concert->tickets as $ticket) {
            if($ticket->user !== null && $ticket->approved) {
                $total += $ticket->vip ? $concert->vip_price : $concert->general_price;
            }
        }
        
        return view('organizers.events.print', [
            'concert' => $concert,
            'carbon' => new Carbon,
            'total' => $total
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
