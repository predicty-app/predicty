<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509184010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4A76ED395 ON account (user_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A4A76ED395 ON account (user_id)');
        $this->addSql('COMMENT ON COLUMN account.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN account.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ad ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_77E0ED589B6B5FBA ON ad (account_id)');
        $this->addSql('ALTER TABLE ad_collection ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_3C436889B6B5FBA ON ad_collection (account_id)');
        $this->addSql('ALTER TABLE ad_set ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_94E34FEB9B6B5FBA ON ad_set (account_id)');
        $this->addSql('ALTER TABLE ad_stats ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_6A273D2FA76ED395 ON ad_stats (user_id)');
        $this->addSql('CREATE INDEX IDX_6A273D2F9B6B5FBA ON ad_stats (account_id)');
        $this->addSql('ALTER TABLE campaign ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_1F1512DD9B6B5FBA ON campaign (account_id)');
        $this->addSql('ALTER TABLE connected_account ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_EEC6115DA76ED395 ON connected_account (user_id)');
        $this->addSql('CREATE INDEX IDX_EEC6115D9B6B5FBA ON connected_account (account_id)');
        $this->addSql('ALTER TABLE conversation ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_8A8E26E99B6B5FBA ON conversation (account_id)');
        $this->addSql('ALTER TABLE conversation_comment ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_F225C669B6B5FBA ON conversation_comment (account_id)');
        $this->addSql('ALTER TABLE daily_revenue ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_36F0BD719B6B5FBA ON daily_revenue (account_id)');
        $this->addSql('ALTER TABLE import ADD account_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_9D4ECE1DA76ED395 ON import (user_id)');
        $this->addSql('CREATE INDEX IDX_9D4ECE1D9B6B5FBA ON import (account_id)');
        $this->addSql('ALTER TABLE "user" ADD account_ids JSONB DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_8D93D649E7927C74 ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE account_id_seq CASCADE');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP INDEX IDX_EEC6115DA76ED395');
        $this->addSql('DROP INDEX IDX_EEC6115D9B6B5FBA');
        $this->addSql('ALTER TABLE connected_account DROP account_id');
        $this->addSql('DROP INDEX IDX_3C436889B6B5FBA');
        $this->addSql('ALTER TABLE ad_collection DROP account_id');
        $this->addSql('DROP INDEX IDX_9D4ECE1DA76ED395');
        $this->addSql('DROP INDEX IDX_9D4ECE1D9B6B5FBA');
        $this->addSql('ALTER TABLE import DROP account_id');
        $this->addSql('DROP INDEX IDX_36F0BD719B6B5FBA');
        $this->addSql('ALTER TABLE daily_revenue DROP account_id');
        $this->addSql('DROP INDEX IDX_8D93D649E7927C74');
        $this->addSql('ALTER TABLE "user" DROP account_ids');
        $this->addSql('DROP INDEX IDX_F225C669B6B5FBA');
        $this->addSql('ALTER TABLE conversation_comment DROP account_id');
        $this->addSql('DROP INDEX IDX_8A8E26E99B6B5FBA');
        $this->addSql('ALTER TABLE conversation DROP account_id');
        $this->addSql('DROP INDEX IDX_1F1512DD9B6B5FBA');
        $this->addSql('ALTER TABLE campaign DROP account_id');
        $this->addSql('DROP INDEX IDX_6A273D2FA76ED395');
        $this->addSql('DROP INDEX IDX_6A273D2F9B6B5FBA');
        $this->addSql('ALTER TABLE ad_stats DROP account_id');
        $this->addSql('DROP INDEX IDX_77E0ED589B6B5FBA');
        $this->addSql('ALTER TABLE ad DROP account_id');
        $this->addSql('DROP INDEX IDX_94E34FEB9B6B5FBA');
        $this->addSql('ALTER TABLE ad_set DROP account_id');
    }
}
