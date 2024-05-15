<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\ConsumerTicketCollection;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\VisitorTicketCollection;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new TicketCollection(Ticket::all());
    }

    public function visitorTickets()
    {
        return new VisitorTicketCollection(Ticket::where('type', 'visitor')->get());
    }

    public function consumerTickets()
    {
        return new ConsumerTicketCollection(Ticket::where('type', 'consumer')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
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
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
