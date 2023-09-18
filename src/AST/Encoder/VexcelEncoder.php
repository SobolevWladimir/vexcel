<?php

namespace SobolevWladimir\Vexcel\AST\Encoder;

use SobolevWladimir\Vexcel\AST\DataType\FloatExpression;
use SobolevWladimir\Vexcel\AST\DataType\IntExpression;
use SobolevWladimir\Vexcel\AST\DataType\StringExpression;
use SobolevWladimir\Vexcel\AST\DataType\VariableExpression;
use SobolevWladimir\Vexcel\AST\Expression;
use SobolevWladimir\Vexcel\AST\Function\AbstractFunction;
use SobolevWladimir\Vexcel\AST\Operator\Operator;
use SobolevWladimir\Vexcel\Repository\EmptyVariableRepository;
use SobolevWladimir\Vexcel\Repository\VariableRepositoryInterface;

class VexcelEncoder implements EncoderInterface
{
    public function __construct(
        private VariableRepositoryInterface $repository = new EmptyVariableRepository()
    ) {
    }

    public function encode(Expression $expression): string
    {
        $result = '';

        if ($expression instanceof StringExpression) {
            return $this->encodeString($expression);
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
        $id = $expresion->getIdentifier();

        $name = $this->repository->getNameByIdentifier($id);

        if ($this->isNeedShieldingVariable($name)) {
            return '$' . $name . '$';
        }

        return $name;
    }

    private function isNeedShieldingVariable(string $name): bool
    {
        for ($i = 0; $i < \strlen($name); $i++) {
            $char = $name[$i];

            if ($char === '') {
                continue;
            }

            if (!preg_match('/[a-zA-Zа-яА-Я._0-9]/i', $char)) {
                return true;
            }
        }

        return false;
    }

    private function encodeFunction(AbstractFunction $fun): string
    {
        $result = $fun->getToken()->value;
        $result .= '(';
        $args = $fun->getArgs();

        for ($i = 0; $i < \count($args) - 1; $i++) {
            $arg = $args[$i];
            $argName = $this->encode($arg);
            $result .= ' ' . $argName . ';';
        }

        if (\count($args) > 0) {
            $last = end($args);
            $result .= ' ' . $this->encode($last);
        }

        $result .= ')';

        return $result;
    }

    private function encodeOperator(Operator $expression): string
    {
        $left = $this->encode($expression->getLeftExpression());
        $right = $this->encode($expression->getRightExpression());
        $operator = (string)$expression->getToken()->value;

        return "{$left} {$operator} {$right}";
    }
}
