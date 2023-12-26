<?php

namespace App\Http\Resources;

use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Election $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'info' => $this->info   ,
            'publishFrom' => $this->publish_from,
            'startFrom' => $this->start_from,
            'endTo' => $this->end_to,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
