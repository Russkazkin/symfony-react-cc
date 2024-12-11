<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305025853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Upgrade User as security class';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE users ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "users" DROP roles');
        $this->addSql('ALTER TABLE "users" DROP password');
        $this->addSql('ALTER TABLE "users" ALTER email TYPE VARCHAR(255)');
    }
}
