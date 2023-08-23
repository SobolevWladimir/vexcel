<?php

namespace SobolevWladimir\Vexcel\Repository;

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
