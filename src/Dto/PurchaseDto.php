<?php declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\BaseDto;

final class PurchaseDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $paymentProcessor;
}
