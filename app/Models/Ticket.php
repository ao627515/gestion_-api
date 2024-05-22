<?php

namespace App\Models;

use Dotenv\Util\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    public function generateTicketId(): string
    {
        $date = now();
        $ticketId = $date->year . $date->day . $date->month . $date->hour . $date->minute . $date->second . $date->millisecond . $this->id;
        return $ticketId;
    }

    public static function _generateTicketId(): string
    {
        $lastTicket = static::orderBy('id', 'desc')->first();

        if ($lastTicket) {
            $lastId = $lastTicket->id;
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

        $date = now();
        $year = $date->year;
        $day = self::padTo(2,$date->day);
        $month = self::padTo(2,$date->month);
        $hour = self::padTo(2,$date->hour);
        $minute = self::padTo(2,$date->minute);
        $second = self::padTo(2,$date->second);
        $millisecond = self::padTo(3,$date->millisecond);

        $ticketId = $year . $day . $month . $hour . $minute . $second . $millisecond . $newId;

        return $ticketId;
    }

    private static function padTo(int $length, $number)
    {
        $number = is_string($number) ? $number : strval($number);
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    public static function ticketsCount(string $type = '', string $date = '')
    {
        return Ticket::when(!empty($type), function ($query) use ($type) {
            $query->where('type', $type);
        })->when(!empty($date), function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        })->count();
    }


    public function halls()
    {
        return $this->belongsToMany(Hall::class, 'hall_ticket', 'ticket_id', 'hall_id', 'id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
