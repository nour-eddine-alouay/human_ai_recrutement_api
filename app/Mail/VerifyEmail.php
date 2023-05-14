<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = $this->verificationUrl();

        return $this->markdown('emails.verify', [
            'user' => $this->user,
            'verificationUrl' => $verificationUrl
        ]);
    }

    protected function verificationUrl()
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $this->user->getKey()]
        );

        return str_replace('http://', 'https://', $url);
    }
}