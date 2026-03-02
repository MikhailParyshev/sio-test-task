<?php declare(strict_types=1);

namespace App\Factory;

use App\Contract\PaymentProcessorInterface;
use App\Service\Payment\PaypalPaymentProcessor;
use App\Service\Payment\StripePaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as AdaptedPaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as AdaptedStripePaymentProcessor;

final class PaymentProcessorFactory
{
    public static function create(string $paymentProcessor): ?PaymentProcessorInterface
    {
        return match ($paymentProcessor) {
            'paypal' => new PaypalPaymentProcessor(new AdaptedPaypalPaymentProcessor),
            'stripe' => new StripePaymentProcessor(new AdaptedStripePaymentProcessor),
            default => null,
        };
    }
}
