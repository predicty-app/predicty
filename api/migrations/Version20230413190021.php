<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413190021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_77E0ED58D46F4E3 ON ad (started_at)');
        $this->addSql('CREATE INDEX IDX_3C43688D46F4E3 ON ad_collection (started_at)');
        $this->addSql('CREATE INDEX IDX_94E34FEBD46F4E3 ON ad_set (started_at)');
        $this->addSql('CREATE INDEX IDX_1F1512DDD46F4E3 ON campaign (started_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_94E34FEBD46F4E3');
        $this->addSql('DROP INDEX IDX_3C43688D46F4E3');
        $this->addSql('DROP INDEX IDX_1F1512DDD46F4E3');
        $this->addSql('DROP INDEX IDX_77E0ED58D46F4E3');
    }
}
