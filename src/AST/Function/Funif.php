<?php

namespace SobolevWladimir\Vexcel\AST\Function;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class Funif extends AbstractFunction
{
    protected function getNumberArguments(): int
    {
        return 3;
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        $condition = $this->args[0]->calculate($repository);

        if ($condition) {
            return $this->args[1]->calculate($repository);
        }

        return $this->args[2]->calculate($repository);
    }
}
