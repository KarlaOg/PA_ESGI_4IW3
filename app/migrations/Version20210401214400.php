<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401214400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9589D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT FK_3D9F8CA39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F9589D86650F');
        $this->addSql('ALTER TABLE chat CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE influencer DROP FOREIGN KEY FK_3D9F8CA39D86650F');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment_user DROP FOREIGN KEY FK_AC10413DA76ED395');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }
}
