<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217102834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_schedule ADD total_amount_amount INT NOT NULL');
        $this->addSql('ALTER TABLE payment_schedule ADD total_amount_currency VARCHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE payment_schedule_item ADD amount_currency VARCHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE payment_schedule_item ALTER due_date TYPE DATE');
        $this->addSql('ALTER TABLE payment_schedule_item RENAME COLUMN amount TO amount_amount');
        $this->addSql('COMMENT ON COLUMN payment_schedule_item.due_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE product ADD price_currency VARCHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE product DROP currency');
        $this->addSql('ALTER TABLE product RENAME COLUMN price TO price_amount');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_schedule_item DROP amount_currency');
        $this->addSql('ALTER TABLE payment_schedule_item ALTER due_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payment_schedule_item RENAME COLUMN amount_amount TO amount');
        $this->addSql('COMMENT ON COLUMN payment_schedule_item.due_date IS NULL');
        $this->addSql('ALTER TABLE payment_schedule DROP total_amount_amount');
        $this->addSql('ALTER TABLE payment_schedule DROP total_amount_currency');
        $this->addSql('ALTER TABLE product ADD currency VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP price_currency');
        $this->addSql('ALTER TABLE product RENAME COLUMN price_amount TO price');
    }
}
