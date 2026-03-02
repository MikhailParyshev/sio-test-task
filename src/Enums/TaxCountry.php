<?php declare(strict_types=1);

namespace App\Enums;

enum TaxCountry: string 
{
    case GERMANY = 'DE';
    case ITALY = 'IT'; 
    case GREECE = 'GR';
    case FRANCE = 'FR';

    public static function tryFromTaxNumber(string $taxNumber): ?static
    {
        foreach (static::cases() as $case) {
            if (preg_match($case->getNumberPattern(), $taxNumber)) {
                return $case;
            }
        }

        return null;
    }
    
    public function getNumberPattern(): string
    {
        return match($this) {
            self::GERMANY => '/^DE\d{9}$/',
            self::ITALY => '/^IT\d{11}$/',
            self::GREECE => '/^GR\d{9}$/',
            self::FRANCE => '/^FR[a-zA-Z]{2}\d{9}$/'
        };
    }

    public function getTaxPercentage(): int
    {
        return match($this) {
            self::GERMANY => 19,
            self::ITALY => 22,
            self::GREECE => 24,
            self::FRANCE => 20,
        };
    }
}
