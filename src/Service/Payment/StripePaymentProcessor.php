<?php

namespace App\Service\Payment;

use App\Contract\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as AdaptedPaymentProcessor;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private AdaptedPaymentProcessor $adaptedPaymentProcessor
    ) {}

    public function process(float $price): void
    {
        try {
            if (!$this->adaptedPaymentProcessor->processPayment($price)) {
                throw new UnprocessableEntityHttpException('Payment declined');
            };
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException('Payment declined');
        }
    }
}
