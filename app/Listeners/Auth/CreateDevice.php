<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LoggedIn;
use App\Models\Device;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDevice
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
    public function handle(LoggedIn $event): void
    {
        $request = request();
        if ($request?->header('X-Firebase-Token') || $request?->header('X-Apns-Token')) {
            Device::create([
                'user_id' => $event->user->id,
                'access_token_id' => $event->token->accessToken->id,
                'firebase_token' => $request->header('X-Firebase-Token'),
                'apns_token' => $request->header('X-Apns-Token'),
                'name' => $request->header('X-Device-Name') ?? $request->userAgent(),
            ]);
        }
    }
}
