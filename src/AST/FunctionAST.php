<?php

namespace Wladimir\ParserExcel\AST;

use JsonSerializable;

class FunctionAST implements JsonSerializable
{
    public Expression $body;

    public function jsonSerialize(): mixed
    {
        return [
        'body' => $this->body
        ];
    }
}
