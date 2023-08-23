<?php

namespace Tests\Data;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class DummyValueRepository implements ValueRepositoryInterface
{
    public function __construct(private int $value = 1)
    {
    }

    public function getValueByIdentifier(string $identificator): mixed
    {
        return $this->value;
    }
}
