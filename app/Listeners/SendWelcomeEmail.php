<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Send Welcome Email Listener
 *
 * Sends welcome email to newly registered users.
 * Queued for better performance.
 */
class SendWelcomeEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        // TODO: Implement actual email sending via Mail facade
        // For now, just log the action
        Log::info('Welcome email should be sent', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'name' => $event->user->name,
        ]);

        // Example implementation:
        // Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}
