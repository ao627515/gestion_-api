<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessRightResource extends JsonResource
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
            'libelle' => $this->libelle,
            'description' => $this->description, // Correction ici
            'accessRights' => $this->when(
                $this->relationLoaded('accessRights'),
                AccessRightResource::collection($this->accessRights)
            ),
            'createdBy' => $this->whenLoaded('createdBy'),
            'deletedBy' => $this->whenLoaded('deletedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}