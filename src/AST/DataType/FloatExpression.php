<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

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
}
