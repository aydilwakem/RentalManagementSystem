<?php

namespace App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout
{
    protected static bool $logged = false;

    public function handle(Logout $event): void
    {
        // Avoid logging the same event multiple times in one request
        if (self::$logged) {
            return;
        }
        self::$logged = true;

        Log::info('LogSuccessfulLogout listener was triggered');

        /** @var \App\Models\User $user */
        $user = $event->user;

        activity('Auth')
            ->causedBy($user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('A user has logged out');

        Log::info('Logout event triggered for user: ' . $user->email);
        Log::info('User IP: ' . request()->ip());
        Log::info('User Agent: ' . request()->userAgent());
    }
}
