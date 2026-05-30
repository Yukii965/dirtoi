<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        // Sujet de l'email envoyé automatiquement après une commande
        return $this->subject('Confirmation de votre commande - GasyMarket')
                    ->view('emails.order_confirmed');
    }
}