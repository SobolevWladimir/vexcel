<?php

namespace Wladimir\ParserExcel\Expression\DataType;

use Wladimir\ParserExcel\Expression\Expression;
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
