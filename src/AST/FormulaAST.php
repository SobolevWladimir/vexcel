<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FormulaAST implements \JsonSerializable
{
    public function __construct(public ?Expression $body)
    {
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        if ($this->body == null) {
            return null;
        }

        return $this->body->calculate($repository);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'body' => $this->body,
        ];
    }
}
