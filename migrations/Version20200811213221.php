<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811213221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, check_in DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', check_out DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', locale VARCHAR(10) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, guests SMALLINT NOT NULL, message VARCHAR(255) DEFAULT NULL, status VARCHAR(50) NOT NULL, policy TINYINT(1) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_room (event_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_6D541D3071F7E88B (event_id), INDEX IDX_6D541D3054177093 (room_id), PRIMARY KEY(event_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_translation (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, locale VARCHAR(5) NOT NULL, title VARCHAR(255) DEFAULT NULL, teaser LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_1FE096EF71F7E88B (event_id), INDEX IDX_1FE096EFDBF11E1D (idUsersCreator), INDEX IDX_1FE096EF30D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(50) NOT NULL, max_guests SMALLINT NOT NULL, locale VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_translation (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, locale VARCHAR(5) NOT NULL, title VARCHAR(255) DEFAULT NULL, teaser LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_A05CEB5054177093 (room_id), INDEX IDX_A05CEB50DBF11E1D (idUsersCreator), INDEX IDX_A05CEB5030D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_room ADD CONSTRAINT FK_6D541D3071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_room ADD CONSTRAINT FK_6D541D3054177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EF71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EFDBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EF30D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB5054177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB50DBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB5030D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_room DROP FOREIGN KEY FK_6D541D3071F7E88B');
        $this->addSql('ALTER TABLE event_translation DROP FOREIGN KEY FK_1FE096EF71F7E88B');
        $this->addSql('ALTER TABLE event_room DROP FOREIGN KEY FK_6D541D3054177093');
        $this->addSql('ALTER TABLE room_translation DROP FOREIGN KEY FK_A05CEB5054177093');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_room');
        $this->addSql('DROP TABLE event_translation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_translation');
    }
}
