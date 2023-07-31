<?php

namespace Wladimir\ParserExcel\AST\Operator\Binary;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;
use Wladimir\ParserExcel\AST\Operator\Operator;

class MinusOperator extends Operator
{
    public function getName(): string
    {
        return "-";
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return $left - $riht;
        }
        throw $this->getUnsupportedError($left, $riht);
    }
}
