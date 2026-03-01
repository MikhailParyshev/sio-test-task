<?php

namespace App\Contract\Repository;

use App\Entity\Coupon;

interface CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon;
}
