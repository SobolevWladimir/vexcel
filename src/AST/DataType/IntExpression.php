<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class IntExpression extends DataType
{
    public function jsonSerialize(): mixed
    {
        return [
            'type'  => 'IntExpression',
            'value' => $this->value,
        ];
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return (int)$this->token->value;
    }
}
