<?php

namespace App\Service\Discounter;

use App\Contract\DiscounterInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PercentageDiscounter implements DiscounterInterface
{
    public function __construct(private readonly float $discountPercent) {}

    public function apply(float $price): float
    {
        $discounted = $price * (100 - $this->discountPercent) / 100;

        if ($discounted < 0) {
            throw new UnprocessableEntityHttpException('Unprocessable discount');
        }

        return $discounted;
    }
}
