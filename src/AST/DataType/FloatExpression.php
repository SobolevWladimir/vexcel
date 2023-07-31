<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FloatExpression implements Expression
{
    public function __construct(private float $value)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'FloatExpression',
        'value' => $this->value,
        ];
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        return $this->value;
    }
}
