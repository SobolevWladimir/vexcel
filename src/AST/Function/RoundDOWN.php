<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class RoundDOWN extends AbstractFunction
{
    protected function getNumberArguments(): int
    {
        return 2;
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        $value = (float)$this->args[0]->calculate($repository);

        $precision = (int)$this->args[1]->calculate($repository);

        return round($value, $precision, PHP_ROUND_HALF_DOWN);
    }
}
