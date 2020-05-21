<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200521082538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users ADD local_number VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD house_number VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER contact_email TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE users ALTER city TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE users ALTER street TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE users ALTER invoice_number_template TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE company ADD local_number VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD house_number VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ALTER contact_email TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE company ALTER name TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE company ALTER city TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE company ALTER street TYPE VARCHAR(40)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP local_number');
        $this->addSql('ALTER TABLE users DROP house_number');
        $this->addSql('ALTER TABLE users ALTER contact_email TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE users ALTER city TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE users ALTER street TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER invoice_number_template TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE company DROP local_number');
        $this->addSql('ALTER TABLE company DROP house_number');
        $this->addSql('ALTER TABLE company ALTER contact_email TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE company ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE company ALTER city TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE company ALTER street TYPE VARCHAR(255)');
    }
}
