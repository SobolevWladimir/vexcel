<?php

namespace Wladimir\ParserExcel\AST\Operator\Binary;

use Wladimir\ParserExcel\Repository\VariableRepository;
use Wladimir\ParserExcel\AST\Operator\Operator;

/** Возвести в степень*/
class ToPowerOperator extends Operator
{
    public function getName(): string
    {
        return "^";
    }

    public function calculate(VariableRepository $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return pow($left, $riht);
        }
        throw $this->getUnsupportedError($left, $riht);
    }
}