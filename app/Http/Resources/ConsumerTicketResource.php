<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsumerTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ticket_halls = new HallCollection($this->halls);
        $halls = [];

        foreach ($ticket_halls as $item) {
            if ($this->type === 'consumer') {
                $halls[] = $item;
            }
        }

        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'type' => $this->type,
            'created_by' => new UserResource($this->created_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'halls' => $halls,
            'total' => $this->total,
            'number' => $this->number
        ];
    }
}
