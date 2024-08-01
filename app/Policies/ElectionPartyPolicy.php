<?php

namespace App\Policies;

use App\Enum\UserRoleEnum;
use App\Models\User;

class ElectionPartyPolicy
{
    public function store(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }

    public function update(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }
}
