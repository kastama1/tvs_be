<?php

namespace App\Policies;

use App\Enum\UserRoleEnum;
use App\Models\Election;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ElectionPolicy
{
    public function listPublish(User $user): bool
    {
        return $user->role === UserRoleEnum::VOTER;
    }

    public function listAll(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }

    public function view(User $user, Election $election): bool
    {
        $isAdmin = $user->role === UserRoleEnum::ADMIN;
        $isVoter = $user->role === UserRoleEnum::VOTER;
        $isPublished = $election->published;
        $userVoted = $election->votes()->where('user_id', '=', Auth::user()->id)->count() > 0;

        return $isAdmin || ($isVoter && ($isPublished || $userVoted));
    }

    public function create(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }

    public function update(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }

    public function vote(User $user, Election $election): bool
    {
        $userVoted = $election->votes()->where('user_id', '=', Auth::user()->id)->count() > 0;

        return $user->role === UserRoleEnum::VOTER && $election->active || $userVoted;
    }

    public function downloadVotes(User $user, Election $election): bool
    {
        return $user->role === UserRoleEnum::ADMIN && $election->ended;
    }

    public function assignOptions(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }
}
