<?php

namespace App\Http\Resources;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Candidate $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'campaign' => $this->campaign,
            'images' => FileResource::collection($this->images),
            'electionParty' => $this->electionParty ? ElectionPartyWithoutCandidatesResource::make($this->electionParty) : null,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
