<?php

declare(strict_types=1);

namespace App\Entity;

interface DataProviderAware extends AccountMember
{
    public function setDataProvider(DataProvider $dataProvider): void;

    public function getDataProvider(): DataProvider;
}
