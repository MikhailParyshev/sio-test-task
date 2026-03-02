<?php declare(strict_types=1);

namespace App\Entity;

use App\Helper\PriceHelper;
use Brick\Money\Money;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'bigint')]
    private int $priceAmount;

    #[ORM\Column(length: 3)]
    private string $currencyCode = PriceHelper::DEFAULT_CURRENCY;

    public function __construct(string $name, Money $price)
    {
        $this->name = $name;
        $this->setPriceMoney($price);
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPriceMoney(Money $price): self
    {
        $this->priceAmount = $price->getMinorAmount()->toInt();
        $this->currencyCode = $price->getCurrency()->getCurrencyCode();
        return $this;
    }

    public function getPriceMoney(): Money
    {
        return Money::ofMinor($this->priceAmount, $this->currencyCode);
    }
}

