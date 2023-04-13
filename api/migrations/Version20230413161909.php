<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413161909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ADD started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ad ADD ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN ad.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ad_collection ADD started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_collection ADD ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN ad_collection.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ad_set ADD started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_set ADD ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN ad_set.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE campaign ADD started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign ADD ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN campaign.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN campaign.ended_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_collection DROP started_at');
        $this->addSql('ALTER TABLE ad_collection DROP ended_at');
        $this->addSql('ALTER TABLE campaign DROP started_at');
        $this->addSql('ALTER TABLE campaign DROP ended_at');
        $this->addSql('ALTER TABLE ad_set DROP started_at');
        $this->addSql('ALTER TABLE ad_set DROP ended_at');
        $this->addSql('ALTER TABLE ad DROP started_at');
        $this->addSql('ALTER TABLE ad DROP ended_at');
    }
}
