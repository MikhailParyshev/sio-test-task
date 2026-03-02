<?php declare(strict_types=1);

namespace App\Factory;

use App\Contract\DiscounterInterface;
use App\Entity\Coupon;
use App\Helper\PriceHelper;
use App\Service\Discounter\FixedDiscounter;
use App\Service\Discounter\PercentageDiscounter;
use Brick\Money\Currency;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class DiscounterFactory
{
    public static function fromCoupon(Coupon $coupon): DiscounterInterface
    {
        return match ($coupon->getType()) {
            Coupon::TYPE_FIXED => new FixedDiscounter(PriceHelper::createMoney($coupon->getValue(), Currency::of(PriceHelper::DEFAULT_CURRENCY))),
            Coupon::TYPE_PERCENTAGE => new PercentageDiscounter($coupon->getValue()),
            default => throw new UnprocessableEntityHttpException('Unknown coupon type')
        };
    }
}
