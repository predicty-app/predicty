<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315195739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaign ADD data_provider VARCHAR(255) DEFAULT \'OTHER\' NOT NULL');
        $this->addSql('ALTER TABLE data_provider_credentials RENAME COLUMN type TO data_provider');
        $this->addSql('ALTER TABLE import ADD data_provider VARCHAR(255) DEFAULT \'OTHER\' NOT NULL');
        $this->addSql('ALTER TABLE import ADD file_import_type VARCHAR(255) DEFAULT \'OTHER\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import DROP data_provider');
        $this->addSql('ALTER TABLE import DROP file_import_type');
        $this->addSql('ALTER TABLE data_provider_credentials RENAME COLUMN data_provider TO type');
        $this->addSql('ALTER TABLE campaign DROP data_provider');
    }
}
