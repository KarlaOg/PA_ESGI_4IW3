<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224203945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE brand_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE chat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contract_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE influencer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE application (id INT NOT NULL, status BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE application_influencer (application_id INT NOT NULL, influencer_id INT NOT NULL, PRIMARY KEY(application_id, influencer_id))');
        $this->addSql('CREATE INDEX IDX_4A1CBD4F3E030ACD ON application_influencer (application_id)');
        $this->addSql('CREATE INDEX IDX_4A1CBD4F4AF97FA6 ON application_influencer (influencer_id)');
        $this->addSql('CREATE TABLE brand (id INT NOT NULL, user_id_id INT DEFAULT NULL, application_id INT DEFAULT NULL, domain TEXT DEFAULT NULL, type_brand BOOLEAN DEFAULT NULL, siret INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C52F9589D86650F ON brand (user_id_id)');
        $this->addSql('CREATE INDEX IDX_1C52F9583E030ACD ON brand (application_id)');
        $this->addSql('COMMENT ON COLUMN brand.domain IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE chat (id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, time TIMESTAMP(0) WITH TIME ZONE NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contract (id INT NOT NULL, influencer_id_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E98F2859D772CB29 ON contract (influencer_id_id)');
        $this->addSql('CREATE TABLE influencer (id INT NOT NULL, user_id_id INT DEFAULT NULL, social_network TEXT DEFAULT NULL, siret INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, type JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D9F8CA39D86650F ON influencer (user_id_id)');
        $this->addSql('COMMENT ON COLUMN influencer.social_network IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, message VARCHAR(255) NOT NULL, is_read BOOLEAN NOT NULL, time TIMESTAMP(0) WITH TIME ZONE NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, brand_id_id INT DEFAULT NULL, application_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, decription VARCHAR(255) NOT NULL, date_creation TIMESTAMP(0) WITH TIME ZONE NOT NULL, date_start TIMESTAMP(0) WITH TIME ZONE NOT NULL, date_end TIMESTAMP(0) WITH TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29D6873E24BD5740 ON offer (brand_id_id)');
        $this->addSql('CREATE INDEX IDX_29D6873E3E030ACD ON offer (application_id)');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, offer_id_id INT DEFAULT NULL, payment_ref INT DEFAULT NULL, date_creation TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840DFC69E3BE ON payment (offer_id_id)');
        $this->addSql('CREATE TABLE payment_user (payment_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(payment_id, user_id))');
        $this->addSql('CREATE INDEX IDX_AC10413D4C3A3BB ON payment_user (payment_id)');
        $this->addSql('CREATE INDEX IDX_AC10413DA76ED395 ON payment_user (user_id)');
        $this->addSql('CREATE TABLE user_account (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age TIMESTAMP(0) WITH TIME ZONE NOT NULL, type JSON DEFAULT NULL, image_user VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_253B48AEE7927C74 ON user_account (email)');
        $this->addSql('ALTER TABLE application_influencer ADD CONSTRAINT FK_4A1CBD4F3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_influencer ADD CONSTRAINT FK_4A1CBD4F4AF97FA6 FOREIGN KEY (influencer_id) REFERENCES influencer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9589D86650F FOREIGN KEY (user_id_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9583E030ACD FOREIGN KEY (application_id) REFERENCES application (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859D772CB29 FOREIGN KEY (influencer_id_id) REFERENCES influencer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT FK_3D9F8CA39D86650F FOREIGN KEY (user_id_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E24BD5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DFC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413D4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413DA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE offer_brand');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE application_influencer DROP CONSTRAINT FK_4A1CBD4F3E030ACD');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT FK_1C52F9583E030ACD');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873E3E030ACD');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873E24BD5740');
        $this->addSql('ALTER TABLE application_influencer DROP CONSTRAINT FK_4A1CBD4F4AF97FA6');
        $this->addSql('ALTER TABLE contract DROP CONSTRAINT FK_E98F2859D772CB29');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DFC69E3BE');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT FK_AC10413D4C3A3BB');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT FK_1C52F9589D86650F');
        $this->addSql('ALTER TABLE influencer DROP CONSTRAINT FK_3D9F8CA39D86650F');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT FK_AC10413DA76ED395');
        $this->addSql('DROP SEQUENCE application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE brand_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE chat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contract_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE influencer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_account_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age TIMESTAMP(0) WITH TIME ZONE NOT NULL, type JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('CREATE TABLE offer_brand (offer_id INT NOT NULL, brand_id INT NOT NULL, PRIMARY KEY(offer_id, brand_id))');
        $this->addSql('CREATE INDEX idx_9f7f84b153c674ee ON offer_brand (offer_id)');
        $this->addSql('CREATE INDEX idx_9f7f84b144f5d008 ON offer_brand (brand_id)');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE application_influencer');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE influencer');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_user');
        $this->addSql('DROP TABLE user_account');
    }
}
