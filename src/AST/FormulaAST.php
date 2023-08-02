<?php

namespace Wladimir\ParserExcel\AST;

use JsonSerializable;

class FormulaAST implements JsonSerializable
{
    public function __construct(public Expression $body)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'body' => $this->body
        ];
    }
}
