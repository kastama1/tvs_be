<?php

namespace App\Http\Resources;

use App\Models\ElectionParty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElectionPartyWithoutCandidatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ElectionParty $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'campaign' => $this->campaign,
            'images' => FileResource::collection($this->images),
            'votes' => $this->votes_count ? $this->votes_count : 0,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
