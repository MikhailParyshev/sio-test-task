<?php declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\CouponRepositoryInterface;
use App\Entity\Coupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CouponRepository extends ServiceEntityRepository implements CouponRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    public function findByCode(string $code): ?Coupon
    {
        return $this->findOneBy(['code' => $code]);
    }
}
