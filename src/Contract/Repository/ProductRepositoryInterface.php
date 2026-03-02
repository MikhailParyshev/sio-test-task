<?php declare(strict_types=1);

namespace App\Contract\Repository;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
}
