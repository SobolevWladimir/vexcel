<?php

namespace SobolevWladimir\Vexcel\AST\Function;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

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
