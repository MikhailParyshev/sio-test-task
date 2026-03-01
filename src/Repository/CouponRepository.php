<?php

namespace App\Repository;

use App\Contract\Repository\CouponRepositoryInterface;
use App\Entity\Coupon;

class CouponRepository implements CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon
    {
        foreach ($this->getCoupons() as $coupon) {
            if ($coupon->getCode() === $code) {
                return $coupon;
            }
        }

        return null;
    }

    private function getCoupons(): array
    {
        return array_map(
            function($item) {
                return new Coupon(...$item);
            },
            [
                ['P10', Coupon::TYPE_PERCENTAGE, 10],
                ['P20', Coupon::TYPE_PERCENTAGE, 20],
                ['F15', Coupon::TYPE_FIXED, 15],
            ]
        );
    }
}
