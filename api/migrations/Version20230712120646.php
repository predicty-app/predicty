<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712120646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_invitation (id UUID NOT NULL, email VARCHAR(255) NOT NULL, valid_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, account_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8693069B6B5FBA ON account_invitation (account_id)');
        $this->addSql('CREATE INDEX IDX_D869306E7927C74 ON account_invitation (email)');
        $this->addSql('COMMENT ON COLUMN account_invitation.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN account_invitation.valid_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN account_invitation.account_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN account_invitation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN account_invitation.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN account_invitation.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN account_ids TO accounts');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account_invitation');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN accounts TO account_ids');
    }
}
