<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Spokes\PaymentSpoke;

class HandlePaymentProcessed
{
    public function __construct(private PaymentSpoke $paymentSpoke) {}

    public function handle(PaymentProcessed $event): void
    {
        $this->paymentSpoke->onPaymentProcessed($event->order, $event->payment);
    }
}
