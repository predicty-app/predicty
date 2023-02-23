<?php

declare(strict_types=1);

namespace App\Service\Param;

use App\Repository\ParamRepository;

/**
 * Allows storing application parameters by other services.
 */
class ParamService
{
    public function __construct(private ParamRepository $paramRepository)
    {
    }

    public function get(string $name): ?string
    {
        return $this->paramRepository->findByName($name)?->getValue();
    }

    public function getAsInt(string $name): int
    {
        return (int) $this->get($name);
    }

    public function set(string $name, ?string $value = null): void
    {
        $param = $this->paramRepository->findByNameOrCreate($name);
        $param->setValue($value);
        $this->paramRepository->saveAndFlush($param);
    }
}
