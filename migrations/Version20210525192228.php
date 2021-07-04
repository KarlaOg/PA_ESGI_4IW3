<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525192228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application CHANGE offer_id offer_id INT NOT NULL');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F9589D86650F');
        $this->addSql('DROP INDEX IDX_1C52F9589D86650F ON brand');
        $this->addSql('ALTER TABLE brand CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F958A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C52F958A76ED395 ON brand (user_id)');
        $this->addSql('ALTER TABLE chat CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE influencer DROP FOREIGN KEY FK_3D9F8CA39D86650F');
        $this->addSql('DROP INDEX IDX_3D9F8CA39D86650F ON influencer');
        $this->addSql('ALTER TABLE influencer CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT FK_3D9F8CA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D9F8CA3A76ED395 ON influencer (user_id)');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E24BD5740');
        $this->addSql('ALTER TABLE offer CHANGE brand_id_id brand_id_id INT NOT NULL, CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E24BD5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_admin TINYINT(1) NOT NULL, CHANGE age age DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application CHANGE offer_id offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F958A76ED395');
        $this->addSql('DROP INDEX IDX_1C52F958A76ED395 ON brand');
        $this->addSql('ALTER TABLE brand CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9589D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C52F9589D86650F ON brand (user_id_id)');
        $this->addSql('ALTER TABLE chat CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE influencer DROP FOREIGN KEY FK_3D9F8CA3A76ED395');
        $this->addSql('DROP INDEX IDX_3D9F8CA3A76ED395 ON influencer');
        $this->addSql('ALTER TABLE influencer CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE influencer ADD CONSTRAINT FK_3D9F8CA39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D9F8CA39D86650F ON influencer (user_id_id)');
        $this->addSql('ALTER TABLE notification CHANGE time time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E24BD5740');
        $this->addSql('ALTER TABLE offer CHANGE brand_id_id brand_id_id INT DEFAULT NULL, CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E24BD5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP is_admin, CHANGE age age DATETIME NOT NULL');
    }
}
