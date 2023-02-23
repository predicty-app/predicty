<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Param;
use App\Repository\ParamRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \ParamRepository
 */
class ParamRepositoryTest extends KernelTestCase
{
    private ParamRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        static::bootKernel();
        $this->repository = static::$kernel->getContainer()->get(ParamRepository::class);
    }

    public function test_find_by_name_or_create(): void
    {
        $this->repository = static::$kernel->getContainer()->get(ParamRepository::class);

        $this->assertNull($this->repository->findByName('test'));
        $this->assertInstanceOf(Param::class, $this->repository->findByNameOrCreate('test'));
    }

    public function test_save_and_flush(): void
    {
        $this->repository = static::$kernel->getContainer()->get(ParamRepository::class);

        $param = new Param('test', '123');
        $this->repository->saveAndFlush($param);

        $this->assertSame('123', $this->repository->findByName('test')?->getValue());
    }

    public function test_find_by_name(): void
    {
        $this->test_save_and_flush();
    }
}
