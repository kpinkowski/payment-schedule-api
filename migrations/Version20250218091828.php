<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218091828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ad14959723');
        $this->addSql('DROP SEQUENCE product_type_id_seq CASCADE');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP INDEX idx_1afe53934584665a');
        $this->addSql('ALTER TABLE payment_schedule DROP total_amount_amount');
        $this->addSql('ALTER TABLE payment_schedule DROP total_amount_currency');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1AFE53934584665A ON payment_schedule (product_id)');
        $this->addSql('DROP INDEX idx_d34a04ad14959723');
        $this->addSql('ALTER TABLE product ADD date_sold TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE product ADD product_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP product_type_id');
        $this->addSql('COMMENT ON COLUMN product.date_sold IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE product_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP INDEX UNIQ_1AFE53934584665A');
        $this->addSql('ALTER TABLE payment_schedule ADD total_amount_amount INT NOT NULL');
        $this->addSql('ALTER TABLE payment_schedule ADD total_amount_currency VARCHAR(3) NOT NULL');
        $this->addSql('CREATE INDEX idx_1afe53934584665a ON payment_schedule (product_id)');
        $this->addSql('ALTER TABLE product ADD product_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product DROP date_sold');
        $this->addSql('ALTER TABLE product DROP product_type');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ad14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d34a04ad14959723 ON product (product_type_id)');
    }
}
