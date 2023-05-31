<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230602183230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE ad_stats (id UUID NOT NULL, ad_id UUID NOT NULL, results INT NOT NULL, cost_per_result INT NOT NULL, amount_spent INT NOT NULL, currency VARCHAR(255) NOT NULL, date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, user_id UUID NOT NULL, import_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6a273d2f9b6b5fba ON ad_stats (account_id)');
        $this->addSql('CREATE INDEX idx_6a273d2fa76ed395 ON ad_stats (user_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_6a273d2f4f34d596aa9e377a ON ad_stats (ad_id, date)');
        $this->addSql('CREATE INDEX idx_6a273d2f4f34d596 ON ad_stats (ad_id)');
        $this->addSql('CREATE INDEX idx_6a273d2faa9e377a ON ad_stats (date)');
        $this->addSql('COMMENT ON COLUMN ad_stats.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.ad_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.import_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_stats.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE ad (id UUID NOT NULL, external_id VARCHAR(255) NOT NULL, user_id UUID NOT NULL, ad_set_id UUID DEFAULT NULL, campaign_id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, import_id UUID DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_77e0ed589b6b5fba9f75d7b0 ON ad (account_id, external_id)');
        $this->addSql('CREATE INDEX idx_77e0ed589b6b5fba ON ad (account_id)');
        $this->addSql('CREATE INDEX idx_77e0ed58f639f774 ON ad (campaign_id)');
        $this->addSql('CREATE INDEX idx_77e0ed588d141aed ON ad (ad_set_id)');
        $this->addSql('CREATE INDEX idx_77e0ed58a76ed395 ON ad (user_id)');
        $this->addSql('CREATE INDEX idx_77e0ed58d46f4e3 ON ad (started_at)');
        $this->addSql('CREATE INDEX idx_77e0ed589f75d7b0 ON ad (external_id)');
        $this->addSql('COMMENT ON COLUMN ad.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad.ad_set_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad.campaign_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad.import_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_email_verified BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, is_onboarding_complete BOOLEAN DEFAULT false NOT NULL, account_ids JSONB DEFAULT NULL, has_agreed_to_newsletter BOOLEAN DEFAULT false NOT NULL, accepted_terms_of_service_version INT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE ad_set (id UUID NOT NULL, external_id VARCHAR(255) NOT NULL, user_id UUID NOT NULL, campaign_id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, import_id UUID DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_94e34feb9b6b5fba9f75d7b0 ON ad_set (account_id, external_id)');
        $this->addSql('CREATE INDEX idx_94e34feb9b6b5fba ON ad_set (account_id)');
        $this->addSql('CREATE INDEX idx_94e34febf639f774 ON ad_set (campaign_id)');
        $this->addSql('CREATE INDEX idx_94e34feba76ed395 ON ad_set (user_id)');
        $this->addSql('CREATE INDEX idx_94e34febd46f4e3 ON ad_set (started_at)');
        $this->addSql('CREATE INDEX idx_94e34feb9f75d7b0 ON ad_set (external_id)');
        $this->addSql('COMMENT ON COLUMN ad_set.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.campaign_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.import_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_set.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE ad_collection (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) NOT NULL, ads_ids JSONB DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3c436889b6b5fba ON ad_collection (account_id)');
        $this->addSql('CREATE INDEX idx_3c43688a76ed395 ON ad_collection (user_id)');
        $this->addSql('CREATE INDEX idx_3c43688d46f4e3 ON ad_collection (started_at)');
        $this->addSql('CREATE INDEX idx_3c436885e237e06 ON ad_collection (name)');
        $this->addSql('COMMENT ON COLUMN ad_collection.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ad_collection.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE import (id UUID NOT NULL, user_id UUID NOT NULL, status VARCHAR(255) NOT NULL, message TEXT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, type VARCHAR(255) NOT NULL, filename VARCHAR(255) DEFAULT NULL, data_provider VARCHAR(255) DEFAULT \'OTHER\' NOT NULL, file_import_type VARCHAR(255) DEFAULT NULL, result JSON DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9d4ece1d9b6b5fba ON import (account_id)');
        $this->addSql('CREATE INDEX idx_9d4ece1da76ed395 ON import (user_id)');
        $this->addSql('COMMENT ON COLUMN import.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN import.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN import.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN import.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN import.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN import.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN import.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE daily_revenue (id UUID NOT NULL, date DATE NOT NULL, revenue INT NOT NULL, average_order_value INT NOT NULL, currency VARCHAR(3) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, user_id UUID NOT NULL, import_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_36f0bd719b6b5fba ON daily_revenue (account_id)');
        $this->addSql('CREATE INDEX idx_36f0bd71a76ed395 ON daily_revenue (user_id)');
        $this->addSql('CREATE INDEX idx_36f0bd71aa9e377a ON daily_revenue (date)');
        $this->addSql('COMMENT ON COLUMN daily_revenue.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN daily_revenue.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN daily_revenue.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN daily_revenue.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN daily_revenue.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN daily_revenue.import_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN daily_revenue.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE conversation_comment (id UUID NOT NULL, conversation_id UUID NOT NULL, user_id UUID NOT NULL, comment VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f225c669b6b5fba ON conversation_comment (account_id)');
        $this->addSql('CREATE INDEX idx_f225c66a76ed395 ON conversation_comment (user_id)');
        $this->addSql('CREATE INDEX idx_f225c669ac0396 ON conversation_comment (conversation_id)');
        $this->addSql('CREATE INDEX idx_f225c668b8e8428 ON conversation_comment (created_at)');
        $this->addSql('COMMENT ON COLUMN conversation_comment.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN conversation_comment.conversation_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN conversation_comment.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN conversation_comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation_comment.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation_comment.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE conversation (id UUID NOT NULL, user_id UUID NOT NULL, color VARCHAR(255) NOT NULL, date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8a8e26e9a76ed3959b6b5fbaaa9e377a ON conversation (user_id, account_id, date)');
        $this->addSql('CREATE INDEX idx_8a8e26e99b6b5fba ON conversation (account_id)');
        $this->addSql('CREATE INDEX idx_8a8e26e9a76ed395 ON conversation (user_id)');
        $this->addSql('CREATE INDEX idx_8a8e26e9aa9e377a ON conversation (date)');
        $this->addSql('COMMENT ON COLUMN conversation.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN conversation.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN conversation.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE connected_account (id UUID NOT NULL, user_id UUID NOT NULL, data_provider VARCHAR(255) NOT NULL, credentials JSON DEFAULT NULL, is_enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_eec6115d9b6b5fba ON connected_account (account_id)');
        $this->addSql('CREATE INDEX idx_eec6115da76ed395 ON connected_account (user_id)');
        $this->addSql('COMMENT ON COLUMN connected_account.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN connected_account.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN connected_account.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN connected_account.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN connected_account.account_id IS \'(DC2Type:ulid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE account (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7d3656a4a76ed395 ON account (user_id)');
        $this->addSql('COMMENT ON COLUMN account.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN account.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN account.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN account.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_75ea56e016ba31db ON messenger_messages (delivered_at)');
        $this->addSql('CREATE INDEX idx_75ea56e0e3bd61ce ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX idx_75ea56e0fb7336f0 ON messenger_messages (queue_name)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE campaign (id UUID NOT NULL, external_id VARCHAR(255) NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, data_provider VARCHAR(255) DEFAULT \'OTHER\' NOT NULL, import_id UUID DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, account_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_1f1512dd9b6b5fba9f75d7b0 ON campaign (account_id, external_id)');
        $this->addSql('CREATE INDEX idx_1f1512dd9b6b5fba ON campaign (account_id)');
        $this->addSql('CREATE INDEX idx_1f1512dda76ed395 ON campaign (user_id)');
        $this->addSql('CREATE INDEX idx_1f1512ddd46f4e3 ON campaign (started_at)');
        $this->addSql('CREATE INDEX idx_1f1512dd9f75d7b0 ON campaign (external_id)');
        $this->addSql('COMMENT ON COLUMN campaign.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN campaign.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN campaign.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN campaign.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN campaign.import_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN campaign.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN campaign.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN campaign.account_id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE ad_stats');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE ad');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE "user"');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE ad_set');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE ad_collection');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE import');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE daily_revenue');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE conversation_comment');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE conversation');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE connected_account');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE account');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE messenger_messages');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE campaign');
    }
}
