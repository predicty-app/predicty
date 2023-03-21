<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\DataProvider;
use App\Entity\DataProviderType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321060101 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $provider = new DataProvider(DataProviderType::OTHER, 'Default data provider');
        $em->persist($provider);
        $em->flush();

        $this->addSql(sprintf('ALTER TABLE campaign ADD data_provider_id INT DEFAULT %s NOT NULL', $provider->getId()));
        $this->addSql('ALTER TABLE campaign DROP data_provider');
        $this->addSql('CREATE INDEX IDX_1F1512DDF593F7E0 ON campaign (data_provider_id)');
        $this->addSql(sprintf('ALTER TABLE import ADD data_provider_id INT DEFAULT %s NOT NULL', $provider->getId()));
        $this->addSql('ALTER TABLE import DROP data_provider');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1F1512DDF593F7E0');
        $this->addSql('ALTER TABLE campaign ADD data_provider VARCHAR(255) DEFAULT \'OTHER\' NOT NULL');
        $this->addSql('ALTER TABLE campaign DROP data_provider_id');
        $this->addSql('ALTER TABLE import ADD data_provider VARCHAR(255) DEFAULT \'OTHER\' NOT NULL');
        $this->addSql('ALTER TABLE import DROP data_provider_id');
    }
}
