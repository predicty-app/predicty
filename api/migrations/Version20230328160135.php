<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328160135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ALTER ad_set_id DROP NOT NULL');
        $this->addSql('ALTER TABLE ad_stats ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE daily_revenue ALTER user_id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ALTER ad_set_id SET NOT NULL');
        $this->addSql('ALTER TABLE ad_stats ALTER user_id SET DEFAULT 1');
        $this->addSql('ALTER TABLE daily_revenue ALTER user_id SET DEFAULT 1');
    }
}
