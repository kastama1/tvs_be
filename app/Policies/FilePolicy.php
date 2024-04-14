<?php

namespace App\Policies;

use App\Enum\UserRoleEnum;
use App\Models\User;

class FilePolicy
{
    public function destroy(User $user): bool
    {
        return $user->role === UserRoleEnum::ADMIN;
    }
}
