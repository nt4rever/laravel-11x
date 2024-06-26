<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserLoggedIn;

class UserLoggerIdHistory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        $user = $event->user;
        $user->last_login_at = now();
        $user->save();
    }
}
