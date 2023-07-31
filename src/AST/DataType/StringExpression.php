<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class StringExpression implements Expression
{
    public function __construct(private string $value)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'StringExpression',
        'value' => $this->value,
        ];
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        return $this->value;
    }
}
