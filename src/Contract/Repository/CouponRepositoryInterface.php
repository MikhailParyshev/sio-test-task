<?php declare(strict_types=1);

namespace App\Contract\Repository;

use App\Entity\Coupon;

interface CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon;
}
