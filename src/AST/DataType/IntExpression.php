<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

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
}
