<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Spokes\AccountSpoke;

class LogUserRegistration
{
    public function __construct(private AccountSpoke $accountSpoke) {}

    public function handle(UserRegistered $event): void
    {
        $this->accountSpoke->onUserRegistered($event->user);
    }
}
