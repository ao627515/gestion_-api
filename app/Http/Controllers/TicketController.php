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
            $reponse = $this->consumerTicketsStore($request);
        } elseif ($request->ticket_halls == null) {
            $reponse = $this->visitorTicketsStore($request);
        }

        return $reponse;
    }

    public function visitorTicketsStore(StoreTicketRequest $request)
    {
        /**
         * @var array<Ticket>
         */
        $tickets = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $ticket = new Ticket();
            $ticket->type = 'visitor';
            $ticket->price = $request->price;
            $ticket->user_id = $request->user()->id;
            $ticket->ticket_id = $ticket->generateTicketId();
            $ticket->save();
            $tickets[] = $ticket;
        }

        return response()->json([
            'message' => 'visitor ticket store is successful',
            'tickets' => new VisitorTicketCollection($tickets)
        ]);
    }

    public function consumerTicketsStore(StoreTicketRequest $request)
    {
        /**
         * @var array<array<int>> $ticket_halls
         */
        $ticket_halls = $request->ticket_halls;

        /**
         * @var array<Ticket>
         */
        $tickets = [];

        foreach ($ticket_halls as $hallsId) {
            $ticket = new Ticket();
            $ticket->type = 'consumer';
            $ticket->user_id = $request->user()->id;
            $ticket->ticket_id = $ticket->generateTicketId();
            $ticket->save();

            $ticket->halls()->attach($hallsId);
            $tickets[] = $ticket;
        }

        return response()->json([
            'message' => 'consumer ticket store is successful',
            'tickets' => new ConsumerTicketCollection($tickets)
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
