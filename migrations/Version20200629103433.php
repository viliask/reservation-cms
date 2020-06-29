<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629103433 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', location VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_translation (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, locale VARCHAR(5) NOT NULL, title VARCHAR(255) DEFAULT NULL, teaser LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_1FE096EF71F7E88B (event_id), INDEX IDX_1FE096EFDBF11E1D (idUsersCreator), INDEX IDX_1FE096EF30D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_translation (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, locale VARCHAR(5) NOT NULL, title VARCHAR(255) DEFAULT NULL, teaser LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_78059833B83297E7 (reservation_id), INDEX IDX_78059833DBF11E1D (idUsersCreator), INDEX IDX_7805983330D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EF71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EFDBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EF30D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation_translation ADD CONSTRAINT FK_78059833B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation_translation ADD CONSTRAINT FK_78059833DBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation_translation ADD CONSTRAINT FK_7805983330D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_translation DROP FOREIGN KEY FK_1FE096EF71F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_translation');
        $this->addSql('DROP TABLE reservation_translation');
    }
}
