<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308070456 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD is_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE users ALTER status SET DEFAULT \'ожидает активации email\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "users" DROP is_verified');
        $this->addSql('ALTER TABLE "users" ALTER status DROP DEFAULT');
    }
}
