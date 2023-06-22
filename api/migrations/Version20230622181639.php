<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622181639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_stats ADD leads INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE ad_stats ADD clicks INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE ad_stats ADD impressions INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE ad_stats ADD cost_per_click INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE ad_stats ADD cost_per_mil INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE ad_stats RENAME COLUMN results TO conversions');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_stats DROP leads');
        $this->addSql('ALTER TABLE ad_stats DROP clicks');
        $this->addSql('ALTER TABLE ad_stats DROP impressions');
        $this->addSql('ALTER TABLE ad_stats DROP cost_per_click');
        $this->addSql('ALTER TABLE ad_stats DROP cost_per_mil');
        $this->addSql('ALTER TABLE ad_stats RENAME COLUMN conversions TO results');
    }
}
