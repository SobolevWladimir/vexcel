<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FunFalse extends AbstractFunction
{
    protected function getNumberArguments(): int
    {
        return 0;
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        return false;
    }

}
