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
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return object|T
     *
     * @phpstan-return T of object
     */
    public function getReference(string $name, string $className): object
    {
        if ($this->referenceRepository === null) {
            self::fail('Unable to retrieve the reference repository. Make sure to load fixtures first.');
        }

        /** @phpstan-ignore-next-line */
        $object = $this->referenceRepository->getReference($name, $className);
        assert($object instanceof $className);

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
