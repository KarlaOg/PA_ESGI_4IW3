<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302204826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT fk_1c52f9583e030acd');
        $this->addSql('DROP INDEX idx_1c52f9583e030acd');
        $this->addSql('ALTER TABLE brand ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD domaine JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE brand DROP application_id');
        $this->addSql('ALTER TABLE brand DROP type_brand');
        $this->addSql('ALTER TABLE brand RENAME COLUMN domain TO social_network');
        $this->addSql('DROP INDEX idx_3d9f8ca39d86650f');
        $this->addSql('ALTER TABLE influencer ADD username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE influencer ADD type JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE influencer ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D9F8CA39D86650F ON influencer (user_id_id)');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873E24BD5740');
        $this->addSql('DROP INDEX uniq_29d6873e24bd5740');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E24BD5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_29D6873E24BD5740 ON offer (brand_id_id)');
        $this->addSql('ALTER TABLE user_account DROP type');
        $this->addSql('ALTER TABLE user_account DROP nombre_abonnes');
        $this->addSql('ALTER TABLE user_account DROP liens');
        $this->addSql('ALTER TABLE user_account ALTER updated_at SET DEFAULT \'NOW()\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE brand ADD application_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD type_brand BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE brand DROP description');
        $this->addSql('ALTER TABLE brand DROP domaine');
        $this->addSql('ALTER TABLE brand DROP username');
        $this->addSql('ALTER TABLE brand DROP name');
        $this->addSql('ALTER TABLE brand RENAME COLUMN social_network TO domain');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT fk_1c52f9583e030acd FOREIGN KEY (application_id) REFERENCES application (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1c52f9583e030acd ON brand (application_id)');
        $this->addSql('DROP INDEX UNIQ_3D9F8CA39D86650F');
        $this->addSql('ALTER TABLE influencer DROP username');
        $this->addSql('ALTER TABLE influencer DROP type');
        $this->addSql('ALTER TABLE influencer DROP description');
        $this->addSql('CREATE INDEX idx_3d9f8ca39d86650f ON influencer (user_id_id)');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT fk_29d6873e24bd5740');
        $this->addSql('DROP INDEX IDX_29D6873E24BD5740');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT fk_29d6873e24bd5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_29d6873e24bd5740 ON offer (brand_id_id)');
        $this->addSql('ALTER TABLE user_account ADD type JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user_account ADD nombre_abonnes VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_account ADD liens TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_account ALTER updated_at SET DEFAULT \'2021-02-09 20:32:34.429377\'');
        $this->addSql('COMMENT ON COLUMN user_account.liens IS \'(DC2Type:array)\'');
    }
}
