<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    protected static bool $logged = false;

    public function handle(Login $event): void
    {
        // Avoid logging the same event multiple times in one request
        if (self::$logged) {
            return;
        }
        self::$logged = true;


        Log::info('LogSuccessfulLogin listener was triggered');

        /** @var \App\Models\User $user */
        $user = $event->user;

        // Check if the user is null
        if (!$user) {
            Log::warning('Login event triggered without a user.');
            return;
        }

        activity('Auth')
            ->causedBy($user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('A user has logged in');

        Log::info('Login event triggered for user: ' . $user->email);
        Log::info('User IP: ' . request()->ip());
        Log::info('User Agent: ' . request()->userAgent());
    }
}
