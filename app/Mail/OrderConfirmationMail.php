<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load('items');
    }

    public function build()
    {
        $settings = StoreSetting::current();
        $mail = $this->subject("{$settings->store_name} order confirmed — {$this->order->order_number}")
            ->view('emails.order-confirmation', ['storeSettings' => $settings]);

        if ($settings->support_email) {
            $mail->replyTo($settings->support_email, $settings->store_name);
        }

        return $mail;
    }
}
