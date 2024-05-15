<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitorTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'type' => $this->type,
            'price' => $this->price,
            'created_by' => new UserResource($this->created_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
