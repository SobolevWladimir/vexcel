<?php

namespace SobolevWladimir\Vexcel\AST\DataType;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class FloatExpression extends DataType
{
    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return (float)$this->token->value;
    }

    public function getJsonData(): JsonData
    {
        return new JsonData('float', $this->token, []);
    }

    public function getUsedVariables(): array
    {
      return [];
    }
}
