<?php

namespace App\Contract;

interface PaymentProcessorInterface
{
    public function process(float $price): void;
}
