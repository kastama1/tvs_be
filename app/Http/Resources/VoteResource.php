<?php

namespace App\Http\Resources;

use App\Models\Candidate;
use App\Models\ElectionParty;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (is_null($this->resource)) {
            return [];
        }

        /** @var Vote $this */
        return [
            'id' => $this->id,
            'value' => $this->votable->id,
            'isPreferVote' => $this->election->votableType === ElectionParty::class && $this->votable_type === Candidate::class,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
