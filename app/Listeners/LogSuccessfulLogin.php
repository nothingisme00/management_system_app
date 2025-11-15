<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Log;

/**
 * Log Successful Login Listener
 *
 * Logs user login activity for security auditing.
 */
class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        Log::info('User logged in', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'role' => $event->user->role?->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
