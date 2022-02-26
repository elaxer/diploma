<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509141145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_product ADD cost INT NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE cost cost INT NOT NULL, CHANGE discount_price discount_price INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_product DROP cost');
        $this->addSql('ALTER TABLE product CHANGE cost cost DOUBLE PRECISION NOT NULL, CHANGE discount_price discount_price NUMERIC(10, 0) DEFAULT NULL');
    }
}
