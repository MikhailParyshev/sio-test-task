<?php declare(strict_types=1);

namespace App\Contract;

use Brick\Money\Money;

interface PaymentProcessorInterface
{
    public function process(Money $price): void;
}
