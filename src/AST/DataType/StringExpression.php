<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

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
