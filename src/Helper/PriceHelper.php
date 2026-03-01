<?php

namespace App\Helper;

use App\Entity\Coupon;
use App\Factory\DiscounterFactory;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PriceHelper
{
    public static function calculate(
        float $price,
        int $taxPercentage,
        ?Coupon $coupon,
    ): float
    {
        if (isset($coupon)) {
            $discounter = DiscounterFactory::fromCoupon($coupon);
            $price = $discounter->apply($price);
        }

        return self::applyTax($price, $taxPercentage);
    }

    private static function applyTax(float $price, int $taxPercentage): float
    {
        $taxAppliedPrice = $price * (100 - $taxPercentage) / 100;
        if ($taxAppliedPrice < 0) {
            throw new UnprocessableEntityHttpException('Uprocessable price amount');
        }
        return $taxAppliedPrice;
    }
}
