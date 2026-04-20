<?php

namespace App\Spokes;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class AccountSpoke
{
    public function onUserRegistered(User $user): void
    {
        Log::info('AccountSpoke: new account registered', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $user->role,
        ]);
    }

    public function onUserDeleted(User $user): void
    {
        Log::info('AccountSpoke: account deleted', ['user_id' => $user->id]);
    }
}
