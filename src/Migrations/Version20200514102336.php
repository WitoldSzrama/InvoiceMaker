<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514102336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, contact_email VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, nip VARCHAR(10) DEFAULT NULL, regon VARCHAR(9) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, post_code VARCHAR(9) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, st_number VARCHAR(20) DEFAULT NULL, account_number VARCHAR(32) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, terms_accepted TINYINT(1) NOT NULL, base_number INT DEFAULT NULL, base_currency VARCHAR(10) DEFAULT NULL, invoice_number_template VARCHAR(255) DEFAULT NULL, base_vat VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, net_value NUMERIC(10, 2) DEFAULT NULL, gross_value NUMERIC(10, 2) DEFAULT NULL, vat INT NOT NULL, currency VARCHAR(20) NOT NULL, INDEX IDX_D34A04ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contact_email VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, nip VARCHAR(10) DEFAULT NULL, regon VARCHAR(9) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, post_code VARCHAR(9) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, st_number VARCHAR(20) DEFAULT NULL, account_number VARCHAR(32) DEFAULT NULL, INDEX IDX_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, by_company_id INT DEFAULT NULL, for_company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, pay_to DATETIME NOT NULL, comment VARCHAR(255) DEFAULT NULL, invoice_number VARCHAR(255) DEFAULT NULL, sales_date DATETIME DEFAULT NULL, INDEX IDX_90651744476FC9B0 (by_company_id), INDEX IDX_90651744DB01F61A (for_company_id), INDEX IDX_90651744A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_product (invoice_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2193327E2989F1FD (invoice_id), INDEX IDX_2193327E4584665A (product_id), PRIMARY KEY(invoice_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744476FC9B0 FOREIGN KEY (by_company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744DB01F61A FOREIGN KEY (for_company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744A76ED395');
        $this->addSql('ALTER TABLE invoice_product DROP FOREIGN KEY FK_2193327E4584665A');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744476FC9B0');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744DB01F61A');
        $this->addSql('ALTER TABLE invoice_product DROP FOREIGN KEY FK_2193327E2989F1FD');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_product');
    }
}
