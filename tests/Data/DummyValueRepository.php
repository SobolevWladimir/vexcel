<?php

namespace Tests\Data;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

/** @package  */
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
