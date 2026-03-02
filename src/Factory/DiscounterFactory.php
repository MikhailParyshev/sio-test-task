<?php

namespace App\Factory;

use App\Contract\DiscounterInterface;
use App\Entity\Coupon;
use App\Service\Discounter\FixedDiscounter;
use App\Service\Discounter\PercentageDiscounter;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DiscounterFactory
{
    public static function fromCoupon(Coupon $coupon): DiscounterInterface
    {
        return match ($coupon->getType()) {
            Coupon::TYPE_FIXED => new FixedDiscounter($coupon->getValue()),
            Coupon::TYPE_PERCENTAGE => new PercentageDiscounter($coupon->getValue()),
            default => throw new UnprocessableEntityHttpException('Unknown coupon type')
        };
    }
}
