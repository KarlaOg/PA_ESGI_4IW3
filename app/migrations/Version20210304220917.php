<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304220917 extends AbstractMigration
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
        $this->addSql('ALTER TABLE brand ALTER updated_at SET DEFAULT \'NOW()\'');
        $this->addSql('ALTER TABLE brand RENAME COLUMN domaine TO field');
        $this->addSql('ALTER TABLE influencer ALTER updated_at SET DEFAULT \'NOW()\'');
        $this->addSql('ALTER TABLE offer ADD field JSON NOT NULL');
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
        $this->addSql('ALTER TABLE offer DROP field');
        $this->addSql('ALTER TABLE brand ALTER updated_at SET DEFAULT \'2021-03-04 22:03:14.477133\'');
        $this->addSql('ALTER TABLE brand RENAME COLUMN field TO domaine');
        $this->addSql('ALTER TABLE influencer ALTER updated_at SET DEFAULT \'2021-03-04 22:03:14.479946\'');
    }
}
