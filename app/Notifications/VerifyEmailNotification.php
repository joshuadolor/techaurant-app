<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Firebase\JWT\JWT;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $token = JWT::encode([
            'email' => $notifiable->getEmailForVerification(),
            'exp' => now()->addHours(config('app.jwt_expiration_hours'))->timestamp
        ], config('app.key'), 'HS256');

        // Generate the signed URL with the JWT token
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['token' => $token]
        );

        return $url;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $this->verificationUrl($notifiable))
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }
}
