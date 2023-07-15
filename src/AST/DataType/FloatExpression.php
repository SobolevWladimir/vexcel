<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\VariableRepository;

class FloatExpression implements Expression
{
    public function __construct(private float $value)
    {
    }

    public function calculate(VariableRepository $repository): mixed
    {
        return $this->value;
    }
}
