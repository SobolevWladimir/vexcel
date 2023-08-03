<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FloatExpression extends DataType
{
    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'FloatExpression',
        'value' => $this->value,
        ];
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return (float)$this->token->value;
    }
}
