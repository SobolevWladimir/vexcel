<?php

namespace Wladimir\ParserExcel\Expression\Operator;

use Wladimir\ParserExcel\Expression\Expression;

abstract class Operator implements Expression
{
    public function __construct(
        protected Expression $leftExpression,
        protected Expression $rightExpression
    ) {
    }
}
