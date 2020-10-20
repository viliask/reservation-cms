<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201019200163 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'UPDATE room SET type = "pokój"'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'UPDATE room SET type = null'
        );
    }
}
