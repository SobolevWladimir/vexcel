<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FunTrue extends AbstractFunction
{
    protected function getNumberArguments(): int
    {
        return 0;
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return true;
    }
}
