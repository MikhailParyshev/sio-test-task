<?php declare(strict_types=1);

namespace App\Contract;

use Brick\Money\Money;

interface DiscounterInterface
{
    public function apply(Money $price): Money;
}
