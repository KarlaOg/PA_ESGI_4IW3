<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315204300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand ALTER updated_at SET DEFAULT \'NOW()\'');
        $this->addSql('ALTER TABLE influencer ALTER updated_at SET DEFAULT \'NOW()\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE brand ALTER updated_at SET DEFAULT \'2021-03-15 20:41:15.181254\'');
        $this->addSql('ALTER TABLE influencer ALTER updated_at SET DEFAULT \'2021-03-15 20:41:15.187689\'');
    }
}
