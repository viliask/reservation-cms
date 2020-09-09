<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903124030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_offer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(55) NOT NULL, discount DOUBLE PRECISION NOT NULL, min_days INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_promo_offer (room_id INT NOT NULL, promo_offer_id INT NOT NULL, INDEX IDX_D4CA159754177093 (room_id), INDEX IDX_D4CA15979B1951D6 (promo_offer_id), PRIMARY KEY(room_id, promo_offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE room_promo_offer ADD CONSTRAINT FK_D4CA159754177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_promo_offer ADD CONSTRAINT FK_D4CA15979B1951D6 FOREIGN KEY (promo_offer_id) REFERENCES promo_offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room ADD base_price INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_promo_offer DROP FOREIGN KEY FK_D4CA15979B1951D6');
        $this->addSql('DROP TABLE promo_offer');
        $this->addSql('DROP TABLE room_promo_offer');
        $this->addSql('ALTER TABLE room DROP base_price');
    }
}
