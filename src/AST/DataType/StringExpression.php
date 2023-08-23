<?php

namespace SobolevWladimir\Vexcel\AST\DataType;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class StringExpression extends DataType
{
    public function getJsonData(): JsonData
    {
        return new JsonData('string', $this->token);
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return (string)$this->token->value;
    }
}
