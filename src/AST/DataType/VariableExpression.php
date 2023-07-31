<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

/** @package Wladimir\ParserExcel\Expression\DataType */
class VariableExpression implements Expression
{
    public function __construct(private string $identifier)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'VariableExpression',
        'value' => $this->value,
        ];
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        return $repository->getValueByIdentifier($this->identifier);
    }
}
