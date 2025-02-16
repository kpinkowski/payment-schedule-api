<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216115446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE payment_schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_schedule_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment_schedule (id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1AFE53934584665A ON payment_schedule (product_id)');
        $this->addSql('CREATE TABLE payment_schedule_item (id INT NOT NULL, payment_schedule_id INT NOT NULL, amount INT NOT NULL, due_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2570C885287120F ON payment_schedule_item (payment_schedule_id)');
        $this->addSql('CREATE TABLE product_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE payment_schedule ADD CONSTRAINT FK_1AFE53934584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_schedule_item ADD CONSTRAINT FK_2570C885287120F FOREIGN KEY (payment_schedule_id) REFERENCES payment_schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD product_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ALTER price TYPE INT');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04AD14959723 ON product (product_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD14959723');
        $this->addSql('DROP SEQUENCE payment_schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_schedule_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE payment_schedule DROP CONSTRAINT FK_1AFE53934584665A');
        $this->addSql('ALTER TABLE payment_schedule_item DROP CONSTRAINT FK_2570C885287120F');
        $this->addSql('DROP TABLE payment_schedule');
        $this->addSql('DROP TABLE payment_schedule_item');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP INDEX IDX_D34A04AD14959723');
        $this->addSql('ALTER TABLE product DROP product_type_id');
        $this->addSql('ALTER TABLE product ALTER price TYPE DOUBLE PRECISION');
    }
}
