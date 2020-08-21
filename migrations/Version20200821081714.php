<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821081714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fo_dynamics (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, typeId VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, webspaceKey VARCHAR(255) NOT NULL, typeName VARCHAR(255) DEFAULT NULL, data LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, formId INT DEFAULT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_EC8AF0309E50CC11 (formId), INDEX IDX_EC8AF030DBF11E1D (idUsersCreator), INDEX IDX_EC8AF03030D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fo_form_field_translations (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, placeholder VARCHAR(255) DEFAULT NULL, defaultValue LONGTEXT DEFAULT NULL, shortTitle VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, options LONGTEXT DEFAULT NULL, idFormFields INT NOT NULL, INDEX IDX_D3E6716F2C2B00B (idFormFields), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fo_form_fields (id INT AUTO_INCREMENT NOT NULL, keyName VARCHAR(128) NOT NULL, orderNumber INT NOT NULL, type VARCHAR(255) NOT NULL, width VARCHAR(16) NOT NULL, required TINYINT(1) NOT NULL, defaultLocale VARCHAR(5) NOT NULL, idForms INT NOT NULL, INDEX IDX_D544F184EAFB58EA (idForms), UNIQUE INDEX UNIQ_D544F184EAFB58EA6773D512 (idForms, keyName), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fo_form_translation_receivers (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(16) NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, idFormTranslations INT NOT NULL, INDEX IDX_E57BDF29F4EA7C89 (idFormTranslations), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fo_form_translations (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, fromEmail VARCHAR(255) DEFAULT NULL, fromName VARCHAR(255) DEFAULT NULL, toEmail VARCHAR(255) DEFAULT NULL, toName VARCHAR(255) DEFAULT NULL, mailText LONGTEXT DEFAULT NULL, submitLabel VARCHAR(255) DEFAULT NULL, successText LONGTEXT DEFAULT NULL, sendAttachments TINYINT(1) DEFAULT \'0\' NOT NULL, deactivateNotifyMails TINYINT(1) DEFAULT \'0\' NOT NULL, deactivateCustomerMails TINYINT(1) DEFAULT \'0\' NOT NULL, replyTo TINYINT(1) DEFAULT \'0\' NOT NULL, locale VARCHAR(5) NOT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idForms INT NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_8BAF12FFEAFB58EA (idForms), INDEX IDX_8BAF12FFDBF11E1D (idUsersCreator), INDEX IDX_8BAF12FF30D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fo_forms (id INT AUTO_INCREMENT NOT NULL, defaultLocale VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fo_dynamics ADD CONSTRAINT FK_EC8AF0309E50CC11 FOREIGN KEY (formId) REFERENCES fo_forms (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fo_dynamics ADD CONSTRAINT FK_EC8AF030DBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fo_dynamics ADD CONSTRAINT FK_EC8AF03030D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fo_form_field_translations ADD CONSTRAINT FK_D3E6716F2C2B00B FOREIGN KEY (idFormFields) REFERENCES fo_form_fields (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fo_form_fields ADD CONSTRAINT FK_D544F184EAFB58EA FOREIGN KEY (idForms) REFERENCES fo_forms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fo_form_translation_receivers ADD CONSTRAINT FK_E57BDF29F4EA7C89 FOREIGN KEY (idFormTranslations) REFERENCES fo_form_translations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fo_form_translations ADD CONSTRAINT FK_8BAF12FFEAFB58EA FOREIGN KEY (idForms) REFERENCES fo_forms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fo_form_translations ADD CONSTRAINT FK_8BAF12FFDBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fo_form_translations ADD CONSTRAINT FK_8BAF12FF30D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fo_form_field_translations DROP FOREIGN KEY FK_D3E6716F2C2B00B');
        $this->addSql('ALTER TABLE fo_form_translation_receivers DROP FOREIGN KEY FK_E57BDF29F4EA7C89');
        $this->addSql('ALTER TABLE fo_dynamics DROP FOREIGN KEY FK_EC8AF0309E50CC11');
        $this->addSql('ALTER TABLE fo_form_fields DROP FOREIGN KEY FK_D544F184EAFB58EA');
        $this->addSql('ALTER TABLE fo_form_translations DROP FOREIGN KEY FK_8BAF12FFEAFB58EA');
        $this->addSql('DROP TABLE fo_dynamics');
        $this->addSql('DROP TABLE fo_form_field_translations');
        $this->addSql('DROP TABLE fo_form_fields');
        $this->addSql('DROP TABLE fo_form_translation_receivers');
        $this->addSql('DROP TABLE fo_form_translations');
        $this->addSql('DROP TABLE fo_forms');
    }
}
