<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210217090424 extends AbstractMigration
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
        $this->addSql('DROP INDEX uniq_29d6873e24bd5740');
        $this->addSql('CREATE INDEX IDX_29D6873E24BD5740 ON offer (brand_id_id)');
        $this->addSql('ALTER TABLE user_account ALTER updated_at SET DEFAULT \'NOW()\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age TIMESTAMP(0) WITH TIME ZONE NOT NULL, type JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('ALTER TABLE user_account ALTER updated_at SET DEFAULT \'2021-02-09 12:33:40.180079\'');
        $this->addSql('DROP INDEX IDX_29D6873E24BD5740');
        $this->addSql('CREATE UNIQUE INDEX uniq_29d6873e24bd5740 ON offer (brand_id_id)');
    }
}
