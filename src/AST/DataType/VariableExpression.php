<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class VariableExpression extends DataType
{
    public function __construct(private string $identifier, protected Token $token)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type'  => 'VariableExpression',
            'value' => $this->identifier,
        ];
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return $repository->getValueByIdentifier($this->identifier);
    }
}
