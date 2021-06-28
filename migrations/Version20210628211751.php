<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628211751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chat');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, time DATETIME NOT NULL, message VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, receiver_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }
}
