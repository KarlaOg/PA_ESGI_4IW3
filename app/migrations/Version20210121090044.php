<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121090044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT fk_ac10413da76ed395');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT fk_1c52f9589d86650f');
        $this->addSql('ALTER TABLE influencer DROP CONSTRAINT fk_3d9f8ca39d86650f');
        $this->addSql('CREATE TABLE user_account."user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles TEXT NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age TIMESTAMP(0) WITH TIME ZONE NOT NULL, type TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_457EE49EE7927C74 ON user_account."user" (email)');
        $this->addSql('COMMENT ON COLUMN user_account."user".roles IS \'(DC2Type:json)\'');
        $this->addSql('COMMENT ON COLUMN user_account."user".type IS \'(DC2Type:json)\'');
        $this->addSql('DROP TABLE user_account."user"');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT FK_1C52F9589D86650F');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9589D86650F FOREIGN KEY (user_id_id) REFERENCES user_account."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE influencer DROP CONSTRAINT FK_3D9F8CA39D86650F');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT FK_3D9F8CA39D86650F FOREIGN KEY (user_id_id) REFERENCES user_account."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT FK_AC10413DA76ED395');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413DA76ED395 FOREIGN KEY (user_id) REFERENCES user_account."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT FK_1C52F9589D86650F');
        $this->addSql('ALTER TABLE influencer DROP CONSTRAINT FK_3D9F8CA39D86650F');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT FK_AC10413DA76ED395');
        $this->addSql('CREATE TABLE user_account."user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles TEXT NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, age TIMESTAMP(0) WITH TIME ZONE NOT NULL, type TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_457ee49ee7927c74 ON user_account."user" (email)');
        $this->addSql('COMMENT ON COLUMN user_account."user".roles IS \'(DC2Type:json)\'');
        $this->addSql('COMMENT ON COLUMN user_account."user".type IS \'(DC2Type:json)\'');
        $this->addSql('DROP TABLE user_account."user"');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT fk_ac10413da76ed395');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT fk_ac10413da76ed395 FOREIGN KEY (user_id) REFERENCES user_account."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT fk_1c52f9589d86650f');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT fk_1c52f9589d86650f FOREIGN KEY (user_id_id) REFERENCES user_account."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE influencer DROP CONSTRAINT fk_3d9f8ca39d86650f');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT fk_3d9f8ca39d86650f FOREIGN KEY (user_id_id) REFERENCES user_account."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
