<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class IntExpression implements Expression
{
    public function __construct(private int $value)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'IntExpression',
        'value' => $this->value,
        ];
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        return $this->value;
    }
}
