<?php

namespace App\Repository;

use App\Contract\Repository\ProductRepositoryInterface;
use App\Entity\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        foreach ($this->getProducts() as $product) {
            if ($product->getId() === $id) {
                return $product;
            }
        }

        return null;
    }

    private function getProducts(): array
    {
        return array_map(
            function($item) {
                return $this->createProductWithId(...$item);
            },
            [
                [1, 'Iphone', 100],
                [2, 'Earphones', 20],
                [3, 'Case', 10],
            ]
        );
    }

    private function createProductWithId(int $id, string $name, float $price): Product
    {
        $product = new Product($name, $price);

        (new \ReflectionClass(Product::class))
            ->getProperty('id')
            ->setValue($product, $id);
            
        return $product;
    }
}
