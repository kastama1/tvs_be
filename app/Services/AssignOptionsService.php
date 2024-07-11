<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;

class AssignOptionsService
{
    public function assign(Election $election, array $assignData): void
    {
        if ($election->votableType === ElectionParty::class) {
            $election->electionParties()->sync($assignData['options']);
            $election->candidates()->sync($assignData['subOptions']);
        } elseif ($election->votableType === Candidate::class) {
            $election->candidates()->sync($assignData['options']);
        }
    }
}
