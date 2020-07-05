<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629124807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation_translation');
        $this->addSql('ALTER TABLE event ADD room_id INT DEFAULT NULL, ADD check_in DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD check_out DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL, ADD mail VARCHAR(255) NOT NULL, ADD guests SMALLINT NOT NULL, ADD status VARCHAR(50) NOT NULL, ADD policy TINYINT(1) NOT NULL, ADD price DOUBLE PRECISION DEFAULT NULL, DROP start_date, DROP end_date, CHANGE location message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA754177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA754177093 ON event (room_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_translation (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, locale VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, teaser LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_7805983330D07CD5 (idUsersChanger), INDEX IDX_78059833B83297E7 (reservation_id), INDEX IDX_78059833DBF11E1D (idUsersCreator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation_translation ADD CONSTRAINT FK_7805983330D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation_translation ADD CONSTRAINT FK_78059833B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation_translation ADD CONSTRAINT FK_78059833DBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA754177093');
        $this->addSql('DROP INDEX IDX_3BAE0AA754177093 ON event');
        $this->addSql('ALTER TABLE event ADD start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP room_id, DROP check_in, DROP check_out, DROP first_name, DROP last_name, DROP phone, DROP mail, DROP guests, DROP status, DROP policy, DROP price, CHANGE message location VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
