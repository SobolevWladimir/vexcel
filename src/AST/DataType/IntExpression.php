<?php

namespace SobolevWladimir\Vexcel\AST\DataType;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class IntExpression extends DataType
{
    public function getJsonData(): JsonData
    {
        return new JsonData('int', $this->token);
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return (int)$this->token->value;
    }

    public function getUsedVariables(): array
    {
        return [];
    }
}
