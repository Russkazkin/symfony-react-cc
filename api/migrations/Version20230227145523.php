<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227145523 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "adverts" (id SERIAL NOT NULL, category_id INT NOT NULL, city_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price INT NOT NULL, view_count INT DEFAULT NULL, photos JSON DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C88E77712469DE2 ON "adverts" (category_id)');
        $this->addSql('CREATE INDEX IDX_8C88E7778BAC62AF ON "adverts" (city_id)');
        $this->addSql('CREATE INDEX IDX_8C88E777A76ED395 ON "adverts" (user_id)');
        $this->addSql('COMMENT ON COLUMN "adverts".published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "adverts".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "adverts".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "categories" (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, sort INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3AF34668727ACA70 ON "categories" (parent_id)');
        $this->addSql('COMMENT ON COLUMN "categories".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "categories".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "cities" (id SERIAL NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D95DB16B98260155 ON "cities" (region_id)');
        $this->addSql('CREATE TABLE "moderation_records" (id SERIAL NOT NULL, advert_id INT NOT NULL, user_id INT NOT NULL, moderated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B7DE93D9D07ECCB6 ON "moderation_records" (advert_id)');
        $this->addSql('CREATE INDEX IDX_B7DE93D9A76ED395 ON "moderation_records" (user_id)');
        $this->addSql('COMMENT ON COLUMN "moderation_records".moderated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "regions" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "users" (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, patronymic VARCHAR(50) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, call_time VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9444F97DD ON "users" (phone)');
        $this->addSql('COMMENT ON COLUMN "users".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "users".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE "adverts" ADD CONSTRAINT FK_8C88E77712469DE2 FOREIGN KEY (category_id) REFERENCES "categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "adverts" ADD CONSTRAINT FK_8C88E7778BAC62AF FOREIGN KEY (city_id) REFERENCES "cities" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "adverts" ADD CONSTRAINT FK_8C88E777A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "categories" ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES "categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "cities" ADD CONSTRAINT FK_D95DB16B98260155 FOREIGN KEY (region_id) REFERENCES "regions" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "moderation_records" ADD CONSTRAINT FK_B7DE93D9D07ECCB6 FOREIGN KEY (advert_id) REFERENCES "adverts" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "moderation_records" ADD CONSTRAINT FK_B7DE93D9A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "adverts" DROP CONSTRAINT FK_8C88E77712469DE2');
        $this->addSql('ALTER TABLE "adverts" DROP CONSTRAINT FK_8C88E7778BAC62AF');
        $this->addSql('ALTER TABLE "adverts" DROP CONSTRAINT FK_8C88E777A76ED395');
        $this->addSql('ALTER TABLE "categories" DROP CONSTRAINT FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE "cities" DROP CONSTRAINT FK_D95DB16B98260155');
        $this->addSql('ALTER TABLE "moderation_records" DROP CONSTRAINT FK_B7DE93D9D07ECCB6');
        $this->addSql('ALTER TABLE "moderation_records" DROP CONSTRAINT FK_B7DE93D9A76ED395');
        $this->addSql('DROP TABLE "adverts"');
        $this->addSql('DROP TABLE "categories"');
        $this->addSql('DROP TABLE "cities"');
        $this->addSql('DROP TABLE "moderation_records"');
        $this->addSql('DROP TABLE "regions"');
        $this->addSql('DROP TABLE "users"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
