<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Contract\PaymentProcessorInterface;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Enums\TaxCountry;
use App\Helper\PriceHelper;
use App\Service\PurchaseService;
use Brick\Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PurchaseServiceTest extends TestCase
{
    protected PurchaseService $purchaseService;
    protected Product $product;

    protected function setUp(): void
    {
        $this->purchaseService = new PurchaseService;
        $this->product = new Product('Test', self::createTestMoney(100.00));
    }
    
    public function testProcessWithoutCoupon(): void
    {
        $taxCountry = TaxCountry::GERMANY;
        $paymentProcessor = $this->createMock(PaymentProcessorInterface::class);
        $paymentProcessor->expects($this->once())
             ->method('process')
             ->with(self::createTestMoney(119.00));
        
        $this->purchaseService->process($this->product, $taxCountry, $paymentProcessor);
    }

    public function testProcessWithCoupon(): void
    {
        $coupon = new Coupon('P20', Coupon::TYPE_PERCENTAGE, 20.0);
        
        $taxCountry = TaxCountry::GERMANY;
        
        $paymentProcessor = $this->createMock(PaymentProcessorInterface::class);
        $paymentProcessor->expects($this->once())
            ->method('process')
            ->with(self::createTestMoney(95.20));
        
        $this->purchaseService->process($this->product, $taxCountry, $paymentProcessor, $coupon);
    }

    #[DataProvider('taxCountryProvider')]
    public function testProcessDifferentTaxCountries(TaxCountry $taxCountry, Money $expectedPrice): void
    {
        $paymentProcessor = $this->createMock(PaymentProcessorInterface::class);
        $paymentProcessor->expects($this->once())
            ->method('process')
            ->with($expectedPrice);

        $this->purchaseService->process($this->product, $taxCountry, $paymentProcessor);
    }
    
    public static function taxCountryProvider(): array
    {
        return [
            'Germany' => [TaxCountry::GERMANY, self::createTestMoney(119.00)],
            'France'  => [TaxCountry::FRANCE, self::createTestMoney(120.00)],
            'Italy'   => [TaxCountry::ITALY, self::createTestMoney(122.00)],
            'Greece'  => [TaxCountry::GREECE, self::createTestMoney(124.00)],
        ];
    }

    private static function createTestMoney(float $amount): Money
    {
        return Money::of($amount, PriceHelper::DEFAULT_CURRENCY);
    }
}
