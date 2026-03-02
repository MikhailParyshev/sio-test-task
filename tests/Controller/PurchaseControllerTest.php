<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\Coupon;
use App\Enums\TaxCountry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PurchaseControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPurchaseSuccess(): void
    {
        $payload = [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'stripe',
            'couponCode' => 'P20',
        ];

        $this->client->jsonRequest('POST', '/purchase', $payload);
        
        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Success']),
            $this->client->getResponse()->getContent()
        );
    }

    public function testCalculatePriceSuccess(): void
    {
        $payload = [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => null,
        ];

        $this->client->jsonRequest('POST', '/calculate-price', $payload);
        
        self::assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals(119.0, $response['price']);
    }

    public function testPurchaseProductNotFound(): void
    {
        $payload = [
            'product' => 999,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'stripe',
        ];

        $this->client->jsonRequest('POST', '/purchase', $payload);
        
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPurchaseInvalidTaxNumber(): void
    {
        $payload = [
            'product' => 1,
            'taxNumber' => 'INVALID',
            'paymentProcessor' => 'stripe',
        ];

        $this->client->jsonRequest('POST', '/purchase', $payload);
        
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPurchaseInvalidCoupon(): void
    {
        $payload = [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'stripe',
            'couponCode' => 'INVALID',
        ];

        $this->client->jsonRequest('POST', '/purchase', $payload);
        
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPurchaseUnsupportedPaymentProcessor(): void
    {
        $payload = [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'unknown',
        ];

        $this->client->jsonRequest('POST', '/purchase', $payload);
        
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCalculatePriceWithCoupon(): void
    {
        $payload = [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'P20',
        ];

        $this->client->jsonRequest('POST', '/calculate-price', $payload);
        
        self::assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals(119.0, $response['price']);
    }

    public function testInvalidJson(): void
    {
        $this->client->request(
            'POST',
            '/purchase',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json {'
        );
        
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
