<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class Funif extends AbstractFunction
{
    protected function getNumberArguments(): int
    {
        return 3;
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return null;
    }

    public function jsonSerialize(): mixed
    {
    }
}
