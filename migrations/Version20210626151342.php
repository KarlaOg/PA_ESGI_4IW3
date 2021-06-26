<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626151342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }
}
