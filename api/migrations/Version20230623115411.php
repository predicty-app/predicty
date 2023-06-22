<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623115411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_stats RENAME TO ad_insights');
        $this->addSql('ALTER INDEX idx_6a273d2fa76ed395 RENAME TO IDX_E0E9FD94A76ED395');
        $this->addSql('ALTER INDEX idx_6a273d2f9b6b5fba RENAME TO IDX_E0E9FD949B6B5FBA');
        $this->addSql('ALTER INDEX idx_6a273d2faa9e377a RENAME TO IDX_E0E9FD94AA9E377A');
        $this->addSql('ALTER INDEX idx_6a273d2f4f34d596 RENAME TO IDX_E0E9FD944F34D596');
        $this->addSql('ALTER INDEX uniq_6a273d2f4f34d596aa9e377a RENAME TO UNIQ_E0E9FD944F34D596AA9E377A');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_insights RENAME TO ad_stats');
        $this->addSql('ALTER INDEX idx_e0e9fd94aa9e377a RENAME TO idx_6a273d2faa9e377a');
        $this->addSql('ALTER INDEX idx_e0e9fd944f34d596 RENAME TO idx_6a273d2f4f34d596');
        $this->addSql('ALTER INDEX uniq_e0e9fd944f34d596aa9e377a RENAME TO uniq_6a273d2f4f34d596aa9e377a');
        $this->addSql('ALTER INDEX idx_e0e9fd94a76ed395 RENAME TO idx_6a273d2fa76ed395');
        $this->addSql('ALTER INDEX idx_e0e9fd949b6b5fba RENAME TO idx_6a273d2f9b6b5fba');
    }
}
