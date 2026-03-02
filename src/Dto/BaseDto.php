<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BaseDto
{
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $product;
    
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $taxNumber;
    
    #[Assert\Type('string')]
    public ?string $couponCode = null;
}
