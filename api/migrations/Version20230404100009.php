<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404100009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ADD import_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_set ADD import_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_stats ADD import_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign ADD import_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE daily_revenue ADD import_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE import ADD result JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_set DROP import_id');
        $this->addSql('ALTER TABLE ad_stats DROP import_id');
        $this->addSql('ALTER TABLE ad DROP import_id');
        $this->addSql('ALTER TABLE import DROP result');
        $this->addSql('ALTER TABLE daily_revenue DROP import_id');
        $this->addSql('ALTER TABLE campaign DROP import_id');
    }
}
