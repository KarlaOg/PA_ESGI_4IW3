<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623194622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E47441B8B65');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4756AE248B');
        $this->addSql('DROP INDEX IDX_A2F98E47441B8B65 ON channel');
        $this->addSql('DROP INDEX IDX_A2F98E4756AE248B ON channel');
        $this->addSql('ALTER TABLE channel DROP user1_id, DROP user2_id');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA72F5A1AA');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAF624B39D');
        $this->addSql('DROP INDEX IDX_659DF2AAF624B39D ON chat');
        $this->addSql('DROP INDEX IDX_659DF2AA72F5A1AA ON chat');
        $this->addSql('ALTER TABLE chat ADD receiver_id INT NOT NULL, DROP channel_id, CHANGE sender_id sender_id INT NOT NULL, CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel ADD user1_id INT DEFAULT NULL, ADD user2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E47441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4756AE248B FOREIGN KEY (user1_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A2F98E47441B8B65 ON channel (user2_id)');
        $this->addSql('CREATE INDEX IDX_A2F98E4756AE248B ON channel (user1_id)');
        $this->addSql('ALTER TABLE chat ADD channel_id INT DEFAULT NULL, DROP receiver_id, CHANGE sender_id sender_id INT DEFAULT NULL, CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAF624B39D ON chat (sender_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA72F5A1AA ON chat (channel_id)');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME NOT NULL');
    }
}
