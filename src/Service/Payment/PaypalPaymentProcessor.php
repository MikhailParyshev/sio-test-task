<?php declare(strict_types=1);

namespace App\Service\Payment;

use App\Contract\PaymentProcessorInterface;
use Brick\Money\Money;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as AdaptedPaymentProcessor;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class PaypalPaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private AdaptedPaymentProcessor $adaptedPaymentProcessor
    ) {}

    public function process(Money $price): void
    {
        try {
            $this->adaptedPaymentProcessor->pay($price->getMinorAmount()->toInt());
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException('Payment declined');
        }
    }
}
