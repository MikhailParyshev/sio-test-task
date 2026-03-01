<?php

namespace App\Service\Discounter;

use App\Contract\DiscounterInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class FixedDiscounter implements DiscounterInterface
{
    public function __construct(private readonly float $fixedDiscount) {}

    public function apply(float $price): float
    {
        $discounted = $price - $this->fixedDiscount;

        if ($discounted < 0) {
            throw new UnprocessableEntityHttpException('Unprocessable discount');
        }

        return $discounted;
    }
}
