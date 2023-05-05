<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516151346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8a8e26e9a76ed395aa9e377a');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A8E26E9A76ED3959B6B5FBAAA9E377A ON conversation (user_id, account_id, date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8A8E26E9A76ED3959B6B5FBAAA9E377A');
        $this->addSql('CREATE UNIQUE INDEX uniq_8a8e26e9a76ed395aa9e377a ON conversation (user_id, date)');
    }
}
