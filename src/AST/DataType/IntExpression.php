<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class IntExpression extends DataType
{
    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'IntExpression',
        'value' => $this->value,
        ];
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        return (int)$this->token->value;
    }
}
