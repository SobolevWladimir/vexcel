<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FunNot extends AbstractFunction
{
    protected function getNumberArguments(): int
    {
        return 1;
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        $value = $this->args[0]->calculate($repository);

        if ($value) {
            return false;
        }

        return true;
    }
}
