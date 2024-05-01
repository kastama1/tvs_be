<?php

namespace App\Policies;

use App\Enum\UserRoleEnum;
use App\Models\User;
use App\Models\Vote;

class VotePolicy
{
    public function view(User $user, Vote $vote): bool
    {
        return $user->role === UserRoleEnum::VOTER && $vote->user->id === $user->id;
    }
}
