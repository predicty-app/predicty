<?php

declare(strict_types=1);

namespace App\Test;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

trait FixturesTrait
{
    private ?ReferenceRepository $referenceRepository;
    private ?AbstractDatabaseTool $databaseTool = null;

    /**
     * @param array<class-string> $fixtures
     */
    public function loadFixtures(array $fixtures = []): void
    {
        static::getClient();
        $this->referenceRepository = $this->getDatabaseTool()->loadFixtures($fixtures)->getReferenceRepository();
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return object|T
     *
     * @phpstan-return ($className is not null ? T : object)
     */
    public function getReference(string $name, ?string $className = null)
    {
        if ($this->referenceRepository === null) {
            self::fail('Unable to retrieve the reference repository. Make sure to load fixtures first.');
        }

        $object = $this->referenceRepository->getReference($name, $className);

        assert($object !== null);

        if ($className !== null) {
            assert($object instanceof $className);
        }

        return $object;
    }

    private function getDatabaseTool(): AbstractDatabaseTool
    {
        if ($this->databaseTool === null) {
            $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        }

        return $this->databaseTool;
    }
}
