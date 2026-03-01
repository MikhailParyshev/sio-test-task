<?php

namespace App\Service;

use App\Contract\PaymentProcessorInterface;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Enums\TaxCountry;
use App\Helper\PriceHelper;

class PurchaseService
{
    public function process(
        Product $product,
        TaxCountry $taxCountry,
        PaymentProcessorInterface $paymentProcessor,
        ?Coupon $coupon = null,
    ): void
    {
        $price = PriceHelper::calculate(
            $product->getPrice(),
            $taxCountry->getTaxPercentage(),
            $coupon,
        );

        $paymentProcessor->process($price);
    }
}
