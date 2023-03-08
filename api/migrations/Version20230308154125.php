<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308154125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ad_collection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ad_collection (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, ads_ids JSONB DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN ad_collection.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ad ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE ad ALTER changed_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE ad_set ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE ad_set ALTER changed_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE ad_stats ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE ad_stats ALTER changed_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE campaign ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE campaign ALTER changed_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".changed_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE ad_collection_id_seq CASCADE');
        $this->addSql('DROP TABLE ad_collection');
        $this->addSql('ALTER TABLE ad_set ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE ad_set ALTER changed_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" DROP changed_at');
        $this->addSql('ALTER TABLE ad ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE ad ALTER changed_at DROP DEFAULT');
        $this->addSql('ALTER TABLE campaign ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE campaign ALTER changed_at DROP DEFAULT');
        $this->addSql('ALTER TABLE ad_stats ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE ad_stats ALTER changed_at DROP DEFAULT');
    }
}
