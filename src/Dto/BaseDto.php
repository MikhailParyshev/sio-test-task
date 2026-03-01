<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BaseDto
{
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public int $product;
    
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $taxNumber;
    
    #[Assert\Type('string')]
    public ?string $couponCode = null;
}
