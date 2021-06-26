<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626165051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_A45BDDC153C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_influencer (application_id INT NOT NULL, influencer_id INT NOT NULL, INDEX IDX_4A1CBD4F3E030ACD (application_id), INDEX IDX_4A1CBD4F4AF97FA6 (influencer_id), PRIMARY KEY(application_id, influencer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, siret INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, social_network LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', field JSON DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, profile_photo VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1C52F958A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, influencer_id_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, INDEX IDX_E98F2859D772CB29 (influencer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE influencer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, social_network LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', siret INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, type JSON DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, profile_photo VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_3D9F8CA3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, channel_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), INDEX IDX_B6BD307F72F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, time DATETIME NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, brand_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, date_creation DATETIME NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, field JSON NOT NULL, INDEX IDX_29D6873E24BD5740 (brand_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT DEFAULT NULL, payment_ref INT DEFAULT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_6D28840DFC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_user (payment_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AC10413D4C3A3BB (payment_id), INDEX IDX_AC10413DA76ED395 (user_id), PRIMARY KEY(payment_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age DATETIME NOT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC153C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE application_influencer ADD CONSTRAINT FK_4A1CBD4F3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application_influencer ADD CONSTRAINT FK_4A1CBD4F4AF97FA6 FOREIGN KEY (influencer_id) REFERENCES influencer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F958A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859D772CB29 FOREIGN KEY (influencer_id_id) REFERENCES influencer (id)');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT FK_3D9F8CA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E24BD5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DFC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413D4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application_influencer DROP FOREIGN KEY FK_4A1CBD4F3E030ACD');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E24BD5740');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F5A1AA');
        $this->addSql('ALTER TABLE application_influencer DROP FOREIGN KEY FK_4A1CBD4F4AF97FA6');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859D772CB29');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC153C674EE');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DFC69E3BE');
        $this->addSql('ALTER TABLE payment_user DROP FOREIGN KEY FK_AC10413D4C3A3BB');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F958A76ED395');
        $this->addSql('ALTER TABLE influencer DROP FOREIGN KEY FK_3D9F8CA3A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE payment_user DROP FOREIGN KEY FK_AC10413DA76ED395');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE application_influencer');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE influencer');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_user');
        $this->addSql('DROP TABLE user');
    }
}
