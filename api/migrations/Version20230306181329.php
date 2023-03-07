<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306181329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ad_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ad_set_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ad_stats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE campaign_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ad (id INT NOT NULL, external_id VARCHAR(255) NOT NULL, user_id INT NOT NULL, ad_set_id INT NOT NULL, campaign_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_77E0ED58A76ED395 ON ad (user_id)');
        $this->addSql('CREATE INDEX IDX_77E0ED588D141AED ON ad (ad_set_id)');
        $this->addSql('CREATE INDEX IDX_77E0ED58F639F774 ON ad (campaign_id)');
        $this->addSql('CREATE INDEX IDX_77E0ED589F75D7B0 ON ad (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_77E0ED58A76ED3959F75D7B0 ON ad (user_id, external_id)');
        $this->addSql('COMMENT ON COLUMN ad.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ad_set (id INT NOT NULL, external_id VARCHAR(255) NOT NULL, user_id INT NOT NULL, campaign_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94E34FEBA76ED395 ON ad_set (user_id)');
        $this->addSql('CREATE INDEX IDX_94E34FEBF639F774 ON ad_set (campaign_id)');
        $this->addSql('CREATE INDEX IDX_94E34FEB9F75D7B0 ON ad_set (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94E34FEBA76ED3959F75D7B0 ON ad_set (user_id, external_id)');
        $this->addSql('COMMENT ON COLUMN ad_set.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ad_stats (id INT NOT NULL, ad_id INT NOT NULL, results INT NOT NULL, cost_per_result INT NOT NULL, amount_spent INT NOT NULL, currency VARCHAR(255) NOT NULL, date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A273D2FAA9E377A ON ad_stats (date)');
        $this->addSql('CREATE INDEX IDX_6A273D2F4F34D596 ON ad_stats (ad_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A273D2F4F34D596AA9E377A ON ad_stats (ad_id, date)');
        $this->addSql('COMMENT ON COLUMN ad_stats.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE campaign (id INT NOT NULL, external_id VARCHAR(255) NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1512DDA76ED395 ON campaign (user_id)');
        $this->addSql('CREATE INDEX IDX_1F1512DD9F75D7B0 ON campaign (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1512DDA76ED3959F75D7B0 ON campaign (user_id, external_id)');
        $this->addSql('COMMENT ON COLUMN campaign.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN campaign.changed_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ad_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ad_set_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ad_stats_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE campaign_id_seq CASCADE');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE ad_set');
        $this->addSql('DROP TABLE ad_stats');
        $this->addSql('DROP TABLE campaign');
    }
}
