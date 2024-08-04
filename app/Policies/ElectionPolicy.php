<?php

namespace App\Policies;

use App\Enum\UserRoleEnum;
use App\Models\Election;
use App\Models\User;

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

        return $isAdmin || ($isVoter && $isPublished);
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
        return $user->role === UserRoleEnum::VOTER && $election->active;
    }

    public function assignOptions(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }
}
