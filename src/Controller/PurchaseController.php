<?php

namespace App\Controller;

use App\Contract\PaymentProcessorInterface;
use App\Contract\Repository\CouponRepositoryInterface;
use App\Contract\Repository\ProductRepositoryInterface;
use App\Dto\PurchaseDto;
use App\Dto\BaseDto;
use App\Entity\Product;
use App\Entity\Coupon;
use App\Enums\TaxCountry;
use App\Factory\PaymentProcessorFactory;
use App\Helper\PriceHelper;
use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function index(
        #[MapRequestPayload]
        PurchaseDto $dto,
        PurchaseService $purchaseService,
        ProductRepositoryInterface $productRepository,
        CouponRepositoryInterface $couponRepository,
    ): JsonResponse
    {
        $purchaseService->process(
            $this->getProduct($dto->product, $productRepository),
            $this->getTaxCountry($dto->taxNumber),
            $this->getPaymentProcessor($dto->paymentProcessor),
            $this->getCoupon($dto->couponCode, $couponRepository),
        );

        return new JsonResponse(['message' => 'Success']);
    }

    #[Route('/calculate-price', name: 'calculate-price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload]
        BaseDto $dto,
        ProductRepositoryInterface $productRepository,
        CouponRepositoryInterface $couponRepository,
    ): JsonResponse
    {
        $price = PriceHelper::calculate(
            $this->getProduct($dto->product, $productRepository)->getPrice(),
            $this->getTaxCountry($dto->taxNumber)->getTaxPercentage(),
            $this->getCoupon($dto->couponCode, $couponRepository),
        );

        return new JsonResponse(['price' => $price]);
    }

    private function getProduct(int $productId, ProductRepositoryInterface $productRepository): Product
    {
        return $productRepository->findById($productId)
            ?? $this->throwUnprocessableException('Product not found');
    }

    private function getTaxCountry(string $taxNumber): TaxCountry
    {
        return TaxCountry::tryFromTaxNumber($taxNumber)
            ?? $this->throwUnprocessableException('Invalid taxNumber');
    }

    private function getPaymentProcessor(string $paymentProcessor): PaymentProcessorInterface
    {
        return PaymentProcessorFactory::create($paymentProcessor)
            ?? $this->throwUnprocessableException('Unsupported paymentProcessor');
    }

    private function getCoupon(?string $couponCode = null, CouponRepositoryInterface $couponRepository): ?Coupon
    {
        if (is_null($couponCode)) {
            return null;
        }

        return $couponRepository->findByCode($couponCode)
            ?? $this->throwUnprocessableException('Invalid couponCode');
    }

    private function throwUnprocessableException(string $message): void
    {
        throw new UnprocessableEntityHttpException($message);
    }
}
