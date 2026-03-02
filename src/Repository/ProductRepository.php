<?php declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\ProductRepositoryInterface;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(int $id): ?Product
    {
        return $this->find($id);
    }
}
