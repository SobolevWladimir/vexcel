<?php

namespace Wladimir\ParserExcel\Expression\Operator;

use Wladimir\ParserExcel\Repository\VariableRepository;

class MinusOperator extends Operator
{
    public function getName(): string
    {
        return "-";
    }

    public function calculate(VariableRepository $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return $left - $riht;
        }
        throw $this->getUnsupportedError($left, $riht);
    }
}
