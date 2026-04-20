<?php

namespace App\Listeners;

use App\Spokes\TransactionSpoke;

class RecordTransaction
{
    public function __construct(private TransactionSpoke $transactionSpoke) {}

    public function handle(object $event): void
    {
        $this->transactionSpoke->createFromOrder($event->order);
    }
}
