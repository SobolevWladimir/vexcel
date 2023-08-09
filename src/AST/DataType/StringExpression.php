<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class StringExpression extends DataType
{
    public function jsonSerialize(): mixed
    {
        return [
            'type'  => 'StringExpression',
            'token' => $this->token,
        ];
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return (string)$this->token->value;
    }
}
