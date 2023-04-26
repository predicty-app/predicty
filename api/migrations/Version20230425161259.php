<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425161259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conversation_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, user_id INT NOT NULL, color VARCHAR(255) NOT NULL, date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E9A76ED395 ON conversation (user_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9AA9E377A ON conversation (date)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A8E26E9A76ED395AA9E377A ON conversation (user_id, date)');
        $this->addSql('COMMENT ON COLUMN conversation.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation.changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE conversation_comment (id INT NOT NULL, conversation_id INT NOT NULL, user_id INT NOT NULL, text VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F225C66A76ED395 ON conversation_comment (user_id)');
        $this->addSql('CREATE INDEX IDX_F225C669AC0396 ON conversation_comment (conversation_id)');
        $this->addSql('CREATE INDEX IDX_F225C668B8E8428 ON conversation_comment (created_at)');
        $this->addSql('COMMENT ON COLUMN conversation_comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation_comment.changed_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conversation_comment_id_seq CASCADE');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE conversation_comment');
    }
}
