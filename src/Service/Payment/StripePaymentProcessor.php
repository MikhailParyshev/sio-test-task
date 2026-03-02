<?php declare(strict_types=1);

namespace App\Service\Payment;

use App\Contract\PaymentProcessorInterface;
use Brick\Money\Money;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as AdaptedPaymentProcessor;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private AdaptedPaymentProcessor $adaptedPaymentProcessor
    ) {}

    public function process(Money $price): void
    {
        try {
            if (!$this->adaptedPaymentProcessor->processPayment($price->getAmount()->toFloat())) {
                throw new UnprocessableEntityHttpException('Payment declined');
            };
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException('Payment declined');
        }
    }
}
