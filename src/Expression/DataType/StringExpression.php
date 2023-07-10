<?php

namespace Wladimir\ParserExcel\Expression\DataType;

use Wladimir\ParserExcel\Expression\Expression;
use Wladimir\ParserExcel\Repository\VariableRepository;

class StringExpression implements Expression
{
    public function __construct(private string $value)
    {
    }

    public function calculate(VariableRepository $repository): mixed
    {
        return $this->value;
    }
}
