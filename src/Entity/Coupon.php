<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Coupon {
    public const string TYPE_FIXED = 'fixed';
    public const string TYPE_PERCENTAGE = 'percentage';

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 100, unique: true)]
    private string $code;
    
    #[ORM\Column]
    private string $type;
    
    #[ORM\Column]
    private float $value;

    public function __construct(string $code, string $type, float $value)
    {
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
    }
    
    public function getId(): ?int { return $this->id; }
    public function getCode(): string { return $this->code; }
    public function getType(): string { return $this->type; }
    public function getValue(): float { return $this->value; }
}
