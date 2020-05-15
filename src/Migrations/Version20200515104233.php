<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200515104233 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, net_value NUMERIC(10, 2) DEFAULT NULL, gross_value NUMERIC(10, 2) DEFAULT NULL, vat INT NOT NULL, currency VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADA76ED395 ON product (user_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, contact_email VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, nip INT DEFAULT NULL, regon INT DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, post_code VARCHAR(9) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, st_number VARCHAR(20) DEFAULT NULL, account_number VARCHAR(32) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, terms_accepted BOOLEAN NOT NULL, base_number INT DEFAULT NULL, base_currency VARCHAR(10) DEFAULT NULL, invoice_number_template VARCHAR(255) DEFAULT NULL, base_vat VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, user_id INT DEFAULT NULL, contact_email VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, nip INT DEFAULT NULL, regon INT DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, post_code VARCHAR(9) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, st_number VARCHAR(20) DEFAULT NULL, account_number VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FBF094FA76ED395 ON company (user_id)');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, by_company_id INT DEFAULT NULL, for_company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, pay_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment VARCHAR(255) DEFAULT NULL, invoice_number VARCHAR(255) DEFAULT NULL, sales_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_90651744476FC9B0 ON invoice (by_company_id)');
        $this->addSql('CREATE INDEX IDX_90651744DB01F61A ON invoice (for_company_id)');
        $this->addSql('CREATE INDEX IDX_90651744A76ED395 ON invoice (user_id)');
        $this->addSql('CREATE TABLE invoice_product (invoice_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(invoice_id, product_id))');
        $this->addSql('CREATE INDEX IDX_2193327E2989F1FD ON invoice_product (invoice_id)');
        $this->addSql('CREATE INDEX IDX_2193327E4584665A ON invoice_product (product_id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744476FC9B0 FOREIGN KEY (by_company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744DB01F61A FOREIGN KEY (for_company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT FK_2193327E4584665A');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744A76ED395');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744476FC9B0');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744DB01F61A');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT FK_2193327E2989F1FD');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_product');
    }
}
