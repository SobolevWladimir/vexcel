<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\VariableRepository;

/** @package Wladimir\ParserExcel\Expression\DataType */
class VariableExpression implements Expression
{
    public function __construct(private string $identifier)
    {
    }

    public function calculate(VariableRepository $repository): mixed
    {
        return $repository->getValueByIdentifier($this->identifier);
    }
}
