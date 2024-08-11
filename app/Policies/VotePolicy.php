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

    public function download(User $user, Vote $vote): bool
    {
        $userRole = $user->role === UserRoleEnum::VOTER;
        $usersVore = $vote->user->id === $user->id;
        $endedElection = $vote->election->ended;

        return $userRole && $usersVore && $endedElection;
    }
}
