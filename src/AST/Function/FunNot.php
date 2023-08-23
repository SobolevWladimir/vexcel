<?php

namespace SobolevWladimir\Vexcel\AST\Function;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

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
