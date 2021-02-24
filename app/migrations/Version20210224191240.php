<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224191240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE offer_brand');
        $this->addSql('ALTER TABLE influencer DROP CONSTRAINT fk_3d9f8ca3a76ed395');
        $this->addSql('DROP INDEX uniq_3d9f8ca3a76ed395');
        $this->addSql('ALTER TABLE influencer DROP user_id');
        $this->addSql('ALTER TABLE user_account ALTER updated_at SET DEFAULT \'NOW()\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age TIMESTAMP(0) WITH TIME ZONE NOT NULL, type JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('CREATE TABLE offer_brand (offer_id INT NOT NULL, brand_id INT NOT NULL, PRIMARY KEY(offer_id, brand_id))');
        $this->addSql('CREATE INDEX idx_9f7f84b153c674ee ON offer_brand (offer_id)');
        $this->addSql('CREATE INDEX idx_9f7f84b144f5d008 ON offer_brand (brand_id)');
        $this->addSql('ALTER TABLE influencer ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT fk_3d9f8ca3a76ed395 FOREIGN KEY (user_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_3d9f8ca3a76ed395 ON influencer (user_id)');
        $this->addSql('ALTER TABLE user_account ALTER updated_at SET DEFAULT \'2021-02-24 19:05:09.684858\'');
    }
}
