<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260302113900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product and coupon tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE product (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price_amount BIGINT NOT NULL,
                currency_code VARCHAR(3) NOT NULL DEFAULT \'EUR\'
            )
        ');

        $this->addSql('
            CREATE TABLE coupon (
                id SERIAL PRIMARY KEY,
                code VARCHAR(100) NOT NULL UNIQUE,
                type VARCHAR(20) NOT NULL,
                value NUMERIC(10,4) NOT NULL
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE coupon');
    }
}
