<?php declare(strict_types=1);

namespace App\Helper;

use App\Entity\Coupon;
use App\Factory\DiscounterFactory;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class PriceHelper
{
    public const DEFAULT_CURRENCY = 'EUR';

    public static function createMoney(float $amount, Currency $currency): Money
    {
        return Money::ofMinor($amount * 100, $currency);
    }

    public static function calculate(
        Money $price,
        int $taxPercentage,
        ?Coupon $coupon,
    ): Money
    {
        if (isset($coupon)) {
            $discounter = DiscounterFactory::fromCoupon($coupon);
            $price = $discounter->apply($price);
        }

        return self::applyTax($price, $taxPercentage);
    }

    private static function applyTax(Money $price, int $taxPercentage): Money
    {
        $taxAppliedPrice = $price->multipliedBy((100 + $taxPercentage) / 100, RoundingMode::Down);

        if ($taxAppliedPrice->isNegative()) {
            throw new UnprocessableEntityHttpException('Uprocessable price amount');
        }

        return $taxAppliedPrice;
    }
}
