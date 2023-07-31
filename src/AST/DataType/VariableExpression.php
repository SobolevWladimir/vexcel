<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

/** @package Wladimir\ParserExcel\Expression\DataType */
class VariableExpression extends DataType
{
    public function __construct(private string $identifier, protected Token $token)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'VariableExpression',
        'value' => $this->identifier,
        ];
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        return $repository->getValueByIdentifier($this->identifier);
    }
}
