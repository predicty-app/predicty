<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323214314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_stats ADD user_id INT NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE daily_revenue ADD user_id INT NOT NULL DEFAULT 1');
        $this->addSql('CREATE INDEX IDX_36F0BD71A76ED395 ON daily_revenue (user_id)');
        $this->addSql('ALTER TABLE import DROP data_provider_credentials_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_stats DROP user_id');
        $this->addSql('ALTER TABLE import ADD data_provider_credentials_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_36F0BD71A76ED395');
        $this->addSql('ALTER TABLE daily_revenue DROP user_id');
    }
}
