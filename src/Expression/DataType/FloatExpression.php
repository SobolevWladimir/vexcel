<?php

namespace Wladimir\ParserExcel\Expression\DataType;

use Wladimir\ParserExcel\Expression\Expression;
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
