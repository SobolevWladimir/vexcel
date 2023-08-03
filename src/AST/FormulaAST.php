<?php

namespace Wladimir\ParserExcel\AST;

use JsonSerializable;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FormulaAST implements JsonSerializable
{
    public function __construct(public Expression $body)
    {
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return $this->body->calculate($repository);
    }
    public function jsonSerialize(): mixed
    {
        return [
        'body' => $this->body
        ];
    }
}
