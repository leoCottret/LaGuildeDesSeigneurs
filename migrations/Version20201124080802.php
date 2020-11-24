<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124080802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD player_id INT NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE intelligence intelligence INT DEFAULT NULL, CHANGE life life INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('CREATE INDEX IDX_3A29410E99E6F5DF ON characters (player_id)');
        $this->addSql('ALTER TABLE players CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(128) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E99E6F5DF');
        $this->addSql('DROP INDEX IDX_3A29410E99E6F5DF ON characters');
        $this->addSql('ALTER TABLE characters DROP player_id, CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE intelligence intelligence INT UNSIGNED DEFAULT NULL, CHANGE life life INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE players CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
