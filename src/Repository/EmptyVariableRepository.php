<?php

namespace Wladimir\ParserExcel\Repository;

class EmptyVariableRepository implements VariableRepositoryInterface
{
    public function getIdentifierByName(string $variableName): string
    {
        return $variableName;
    }

    public function getNameByIdentifier(string $identificator): string
    {
        return $identificator;
    }
}
