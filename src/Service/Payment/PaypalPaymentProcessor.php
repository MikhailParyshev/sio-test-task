<?php

namespace App\Service\Payment;

use App\Contract\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as AdaptedPaymentProcessor;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaypalPaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private AdaptedPaymentProcessor $adaptedPaymentProcessor
    ) {}

    public function process(float $price): void
    {
        try {
            $this->adaptedPaymentProcessor->pay($price);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException('Payment declined');
        }
    }
}
