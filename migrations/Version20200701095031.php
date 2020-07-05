<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200701095031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room_translation (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, locale VARCHAR(5) NOT NULL, title VARCHAR(255) DEFAULT NULL, teaser LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_A05CEB5054177093 (room_id), INDEX IDX_A05CEB50DBF11E1D (idUsersCreator), INDEX IDX_A05CEB5030D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB5054177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB50DBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB5030D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE room ADD enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE room_translation');
        $this->addSql('ALTER TABLE room DROP enabled');
    }
}
