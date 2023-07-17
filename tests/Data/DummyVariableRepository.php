<?php

namespace Tests\Data;

use Wladimir\ParserExcel\Repository\VariableRepository;

/** @package  */
class DummyVariableRepository implements VariableRepository
{
    public function __construct(private int $value = 1)
    {
    }
    public function getIdentifierByName(string $variableName): string
    {
        return $variableName;
    }

    public function getNameByIdentifier(string $identificator): string
    {
        return $identificator;
    }

    public function getValueByIdentifier(string $identificator): mixed
    {
        return $this->value;
    }
}
