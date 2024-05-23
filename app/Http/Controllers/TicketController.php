<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketCollection;
use App\Http\Requests\StoreTicketRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\ConsumerTicketResource;

use App\Http\Resources\VisitorTicketCollection;
use App\Http\Resources\ConsumerTicketCollection;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // init var
        $query = Ticket::query();
        $reponse = null;
        $type = null;
        $order = 'desc';
        $orderBy = 'created_at';

        if ($request->filled('type')) {
            $type = $request->type;
            $query->where('type', $type);
        }

        if ($request->filled('order')) {
            $order = $request->order;
        }

        // if ($request->filled('groupBy')) {
        //     $this->applyGroupBy($query, $request->groupBy);
        //     if ($request->filled('having')) {
        //         $this->applyHaving($query, $request->having);
        //     }
        // }

        if ($request->filled('orderBy')) {
            $orderBy = $request->orderBy;
        }

        if ($request->filled('filter')) {
            $this->filter($query);
        }

        if ($request->filled('limit')) {
            $query->limit(request('limit'));
        }

        $query->orderBy($orderBy, $order);

        if ($request->filled('cheat')) {
            $reponse = $this->cheat();
        } elseif ($request->filled('only')) {
            $reponse = $this->only($query, $type);
        } else {
            $reponse = $this->collections($type, $query);
        }
        return $reponse;
    }

    public function cheat()
    {
        // dd(request('cheat'));

        $response = null;
        switch (request('cheat')) {
            case 'dashboard_report':
                if (request()->filled('filter')) {
                    // si request('filter') = day recupere les donnees par heure d'aujourd'hui
                    // si request('filter') = week recupere les donnees par jour de cette semaine
                    // si request('filter') = month recupere les donnees par jours de ce mois
                    // si request('filter') = year recupere les donnees par mois de cette annee
                    // si request('filter') = full_year recupere les donnees par annee
                }
                $report = Ticket::selectRaw("
                {$this->selectPeriod()} as period,
                COUNT(*) as sales,
                SUM(CASE WHEN tickets.type = 'visitor' THEN 0 ELSE COALESCE(halls.price, 0) END) as income,
                SUM(CASE WHEN tickets.type = 'consumer' THEN 1 ELSE 0 END) as consumer_sales,
                SUM(CASE WHEN tickets.type = 'visitor' THEN 1 ELSE 0 END) as visitor_sales
            ")
                    ->leftJoin('hall_ticket', 'tickets.id', '=', 'hall_ticket.ticket_id')
                    ->leftJoin('halls', 'hall_ticket.hall_id', '=', 'halls.id')
                    ->when(request()->filled('filter'), function ($q) {
                        return $this->filter($q);
                    })
                    ->groupBy('period')
                    ->orderBy('period', 'ASC')
                    ->get();

                $response = response()->json([
                    'periods' => $report->pluck('period')->toArray(),
                    'sales' => $report->pluck('sales')->toArray(),
                    'income' => $report->pluck('income')->toArray(),
                    'consumer_sales' => array_map('intval', $report->pluck('consumer_sales')->toArray()),
                    'visitor_sales' => array_map('intval', $report->pluck('visitor_sales')->toArray()),
                ]);

                break;

            default:
                // Définir une réponse par défaut si nécessaire
                break;
        }

        return $response;
    }

    private function filter(Builder $query)
    {
        $response = null;

        switch (request('filter')) {
            case 'day':
                $response = $query->whereDate('tickets.created_at', now());
                break;
            case 'week':
                $response = $query->whereBetween('tickets.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $response = $query->whereMonth('tickets.created_at', now()->month)
                    ->whereYear('tickets.created_at', now()->year);
                break;
            case 'year':
                $response = $query->whereYear('tickets.created_at', now()->year);
                break;
            case 'full_year':
                $response = $query;
                break;
            default:
                $response = $query;
                break;
        }

        return $response;
    }

    private function selectPeriod()
    {
        switch (request('filter')) {
            case 'day':
                return "DATE_FORMAT(tickets.created_at, '%Y-%m-%dT%H:%i:%s.000Z')";
            case 'week':
                return "DATE_FORMAT(tickets.created_at, '%Y-%m-%d')";
            case 'month':
                return "DATE_FORMAT(tickets.created_at, '%Y-%m-%d')";
            case 'full_month':
                return "DATE_FORMAT(tickets.created_at, '%Y-%m')";
            case 'year':
                return "DATE_FORMAT(tickets.created_at, '%Y-%m')";
            case 'full_year':
                return "DATE_FORMAT(tickets.created_at, '%Y')";
            default:
                return "DATE_FORMAT(tickets.created_at, '%Y-%m-%dT%H:%i:%s.000Z')";
        }
    }



    private function applyGroupBy(Builder $query, string $groupBy)
    {
        $query->groupBy($groupBy);
    }
    private function applyHaving(Builder $query, string $having)
    {
        $query->having($having);
    }

    private function collections(string|null $type, Builder $query)
    {
        $reponse = null;
        switch ($type) {
            case 'visitor':
                $reponse =  new VisitorTicketCollection($query->get());
                break;
            case 'consumer':
                $reponse =  new ConsumerTicketCollection($query->get());
                break;
            default:
                $reponse = new TicketCollection($query->get());
                break;
        }

        return $reponse;
    }

    private function only(Builder $query, string|null $type)
    {
        $reponse = null;

        switch (request('only')) {
            case 'count':
                $reponse = response()->json([
                    'count' => $query->get()->count()
                ]);
                break;
            case 'income':
                // $visitorIncome = 0;
                // $consumerIncome = 0;
                $income = 0;
                foreach ($query->get() as $item) {
                    $income += $item->total;
                }

                $reponse = response()->json([
                    // 'income' => $query->get()->sum('total')
                    // 'income' => $visitorIncome + $consumerIncome
                    'income' => $income
                ]);
                break;
            default:
                $reponse = $query;
                break;
        }

        return $reponse;
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
            $ticket->ticket_id = Ticket::_generateTicketId();
            $ticket->total = $request->total;
            $ticket->number = Ticket::ticketsCount('visitor', now()->toDateString()) + 1;
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

        $consumerTickets = $request->consumerTickets;

        /**
         * @var array<Ticket>
         */
        $tickets = [];

        foreach ($consumerTickets as $consumerTicket) {
            $ticket = new Ticket();
            $ticket->type = 'consumer';
            $ticket->user_id = $request->user()->id;
            $ticket->ticket_id = Ticket::_generateTicketId();
            $ticket->number = Ticket::ticketsCount('consumer', now()->toDateString());
            $ticket->total = $consumerTicket['total'];
            $ticket->save();
            $ticket->halls()->attach($consumerTicket['halls']);
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
