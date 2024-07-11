<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->firstname . " " . $this->lastname,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'role' => $this->role,
            'phone' => $this->phone,
            'registration_number' => $this->registration_number,
            'ref_cinb' => $this->ref_cinb,
            'birthday' => $this->birthday,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
