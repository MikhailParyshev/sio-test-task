<?php declare(strict_types=1);

namespace App\Service\Discounter;

use App\Contract\DiscounterInterface;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class PercentageDiscounter implements DiscounterInterface
{
    public function __construct(private readonly float $discountPercent) {}

    public function apply(Money $price): Money
    {
        $discounted = $price->multipliedBy((100 - $this->discountPercent) / 100, RoundingMode::Down);

        if ($discounted->isNegative()) {
            throw new UnprocessableEntityHttpException('Unprocessable discount');
        }

        return $discounted;
    }
}
