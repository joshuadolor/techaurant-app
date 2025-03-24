<?php

namespace App\Listeners;

use App\Events\UserHasRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendEmailVerification implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(UserHasRegistered $event): void
    {
        $event->user->sendEmailVerificationNotification();
    }

    /**
     * Handle a job failure.
     */
    public function failed(UserHasRegistered $event, \Throwable $exception): void
    {
        // Log the failure or handle it appropriately
        Log::error('Failed to send verification email', [
            'user_id' => $event->user->id,
            'error' => $exception->getMessage()
        ]);
    }
} 