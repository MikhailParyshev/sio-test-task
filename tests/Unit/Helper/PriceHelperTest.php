<?php
declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Entity\Coupon;
use App\Helper\PriceHelper;
use Brick\Money\Currency;
use Brick\Money\Money;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PriceHelperTest extends TestCase
{
    public function testCalculateWithoutCoupon(): void
    {
        $result = PriceHelper::calculate(Money::of(100, PriceHelper::DEFAULT_CURRENCY), 19, null);
        $this->assertEquals(119.0, $result->getAmount()->toFloat());
    }
    
    #[DataProvider('taxCalculationProvider')]
    public function testApplyTax(Money $price, int $taxPercentage, float $expected): void
    {
        $result = PriceHelper::calculate($price, $taxPercentage, null);
        $this->assertEquals($expected, $result->getAmount()->toFloat());
    }
    
    public static function taxCalculationProvider(): array
    {
        return [
            'positive + tax'  => [Money::ofMinor(10000, PriceHelper::DEFAULT_CURRENCY), 19, 119.00],
            'zero price'      => [Money::ofMinor(0, PriceHelper::DEFAULT_CURRENCY),  19, 0.00],
            'no tax'          => [Money::ofMinor(10000, PriceHelper::DEFAULT_CURRENCY), 0,  100.00],
        ];
    }
    
    public function testApplyTaxNegativePriceThrowsException(): void
    {
        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Uprocessable price amount');
        
        PriceHelper::calculate(PriceHelper::createMoney(-10, Currency::of(PriceHelper::DEFAULT_CURRENCY)), 19, null);
    }
    
    #[DataProvider('couponCalculationProvider')]
    public function testCalculateWithCoupon(Money $price, int $taxPercentage, Coupon $coupon, float $expected): void
    {
        $result = PriceHelper::calculate($price, $taxPercentage, $coupon);
        $this->assertEquals($expected, $result->getAmount()->toFloat());
    }

    public static function couponCalculationProvider(): array
    {
        return [
            [Money::ofMinor(10000, PriceHelper::DEFAULT_CURRENCY), 19, new Coupon('P20', Coupon::TYPE_PERCENTAGE, 20.0), 95.2],
            [Money::ofMinor(10000, PriceHelper::DEFAULT_CURRENCY), 19, new Coupon('F20', Coupon::TYPE_FIXED, 15), 101.15],
        ];
    }
}

