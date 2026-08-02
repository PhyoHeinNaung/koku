<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendPostPaymentEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public bool $isNewAccount,
    ) {}

    public function handle(): void
    {
        Mail::to($this->order->email)->send(new OrderConfirmationMail($this->order));

        if ($this->isNewAccount) {
            Password::sendResetLink(['email' => $this->order->email]);
        }
    }
}
