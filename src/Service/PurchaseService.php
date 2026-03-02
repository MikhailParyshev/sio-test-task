<?php declare(strict_types=1);

namespace App\Service;

use App\Contract\PaymentProcessorInterface;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Enums\TaxCountry;
use App\Helper\PriceHelper;

final class PurchaseService
{
    public function process(
        Product $product,
        TaxCountry $taxCountry,
        PaymentProcessorInterface $paymentProcessor,
        ?Coupon $coupon = null,
    ): void
    {
        $price = $product->getPriceMoney();

        $price = PriceHelper::calculate(
            $price,
            $taxCountry->getTaxPercentage(),
            $coupon,
        );

        $paymentProcessor->process($price);
    }
}
