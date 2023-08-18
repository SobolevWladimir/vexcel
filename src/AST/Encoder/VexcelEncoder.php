<?php

namespace Wladimir\ParserExcel\AST\Encoder;

use Wladimir\ParserExcel\AST\DataType\FloatExpression;
use Wladimir\ParserExcel\AST\DataType\IntExpression;
use Wladimir\ParserExcel\AST\DataType\StringExpression;
use Wladimir\ParserExcel\AST\DataType\VariableExpression;
use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\AST\Function\AbstractFunction;
use Wladimir\ParserExcel\AST\Operator\Operator;
use Wladimir\ParserExcel\Repository\EmptyVariableRepository;
use Wladimir\ParserExcel\Repository\VariableRepositoryInterface;

class VexcelEncoder implements EncoderInterface
{
    public function __construct(
        private VariableRepositoryInterface $repository = new EmptyVariableRepository()
    ) {
    }

    public function encode(Expression $expression): string
    {
        $result  = "";
        if ($expression instanceof StringExpression) {
            return  $this->encodeString($expression);
        }
        if ($expression instanceof IntExpression) {
            return $this->encodeInt($expression);
        }
        if ($expression instanceof FloatExpression) {
            return $this->encodeFloat($expression);
        }

        if ($expression instanceof VariableExpression) {
            return $this->encodeVariable($expression);
        }
        if ($expression instanceof AbstractFunction) {
            return $this->encodeFunction($expression);
        }

        if ($expression instanceof Operator) {
            return $this->encodeOperator($expression);
        }

        return $result;
    }

    private function encodeString(StringExpression $expresion): string
    {
        return '"' . $expresion->getToken()->value . '"';
    }

    private function encodeInt(IntExpression $expresion): string
    {
        return $expresion->getToken()->value;
    }

    private function encodeFloat(FloatExpression $expresion): string
    {
        return $expresion->getToken()->value;
    }

    private function encodeVariable(VariableExpression $expresion): string
    {
        $id  = $expresion->getIdentifier();
        return $this->repository->getNameByIdentifier($id);
    }

    private function encodeFunction(AbstractFunction $fun): string
    {
        $result  = $fun->getToken()->value;
        $result .= "(";
        $args  = $fun->getArgs();
        for ($i = 0; $i < count($args) - 1; $i++) {
            $arg = $args[$i];
            $argName  = $this->encode($arg);
            $result .= " " . $argName . ";";
        }
        $last  = end($args);
        $result .= " ".$this->encode($last);

        $result .= ")";
        return $result;
    }


    private function encodeOperator(Operator $expression): string
    {
        $left = $this->encode($expression->getLeftExpression());
        $right = $this->encode($expression->getRightExpression());
        $operator  = (string)$expression->getToken()->value;
        return "$left $operator $right";
    }
}
