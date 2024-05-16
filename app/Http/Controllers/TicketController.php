<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\ConsumerTicketCollection;
use App\Http\Resources\ConsumerTicketResource;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
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
        if ($request->price == null) {
            $this->consumerTicketsStore($request);
        } elseif ($request->ticket_halls == null) {
            $this->visitorTicketsStore($request);
        }
    }

    public function visitorTicketsStore(StoreTicketRequest $request)
    {

        $ticket = new Ticket();
        $ticket->type = 'visitor';
        $ticket->price = $request->price;
        $ticket->user_id = $request->user()->id;
        $ticket->ticket_id = $ticket->generateTicketId();
        $ticket->save();

        return response()->json([
            'message' => 'ticket store is successful',
            'ticket' => new TicketResource($ticket)
        ]);
    }

    public function consumerTicketsStore(StoreTicketRequest $request)
    {
        $ticket = new Ticket();
        $ticket->type = 'consumer';
        $ticket->user_id = $request->user()->id;
        $ticket->ticket_id = $ticket->generateTicketId();
        $ticket->save();

        $ticket->halls()->attach($request->ticket_halls);
        return response()->json([
            'message' => 'ticket store is successful',
            'ticket' => new ConsumerTicketResource($ticket)
        ]);
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
