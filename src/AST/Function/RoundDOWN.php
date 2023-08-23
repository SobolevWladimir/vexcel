<?php

namespace SobolevWladimir\Vexcel\AST\Function;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

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
