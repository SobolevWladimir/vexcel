<?php

namespace Wladimir\ParserExcel\AST\Operator;

use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\AST\Expression;

abstract class Operator implements Expression
{
    public function __construct(
        protected Expression $leftExpression,
        protected Expression $rightExpression
    ) {
    }

    public function getUnsupportedError(mixed $leftValue, mixed $rightValue): UnsupportedError
    {
        $leftType  = gettype($leftValue);
        $rightType  = gettype($rightValue);
        return new  UnsupportedError("Неподдерживаемые типы операндов  $leftType * $rightType");
    }
}