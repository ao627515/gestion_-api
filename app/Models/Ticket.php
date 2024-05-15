<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    public function generateTicketId(): string {
        $date = now();
        $ticketId = $date->year . $date->day . $date->month . $date->hour . $date->minute . $date->second . $date->millisecond.$this->id;
        return $ticketId;
    }

    public static function generateStaticTicketId(): string {
        $lastTicket = static::orderBy('id', 'desc')->first();

        if ($lastTicket) {
            $lastId = $lastTicket->id;
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

        $date = now();
        $ticketId = $date->year . $date->day . $date->month . $date->hour . $date->minute . $date->second . $date->millisecond . $newId;
        return $ticketId;
    }

    public function halls(){
        return $this->belongsToMany(Hall::class, 'hall_ticket', 'ticket_id', 'hall_id', 'id', 'id');
    }

}
