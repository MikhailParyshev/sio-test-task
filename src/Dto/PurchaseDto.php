<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\BaseDto;

class PurchaseDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $paymentProcessor;
}
