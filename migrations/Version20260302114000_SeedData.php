<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260302114000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed initial products and coupons';
    }

    public function up(Schema $schema): void
    {
        // Products
        $this->addSql("INSERT INTO product (name, price_amount, currency_code) VALUES 
            ('iPhone', 10000, 'EUR'),
            ('Earphones', 2000, 'EUR'), 
            ('Case', 1000, 'EUR')");

        // Coupons
        $this->addSql("INSERT INTO coupon (code, type, value) VALUES 
            ('P10', 'percentage', 10),
            ('P20', 'percentage', 20),
            ('F15', 'fixed', 15)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM product');
        $this->addSql('DELETE FROM coupon');
    }
}

