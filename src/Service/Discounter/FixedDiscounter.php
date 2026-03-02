<?php declare(strict_types=1);

namespace App\Service\Discounter;

use App\Contract\DiscounterInterface;
use Brick\Money\Money;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class FixedDiscounter implements DiscounterInterface
{
    public function __construct(private readonly Money $fixedDiscount) {}

    public function apply(Money $price): Money
    {
        $discounted = $price->minus($this->fixedDiscount);

        if ($discounted->isNegative()) {
            throw new UnprocessableEntityHttpException('Unprocessable discount');
        }

        return $discounted;
    }
}
