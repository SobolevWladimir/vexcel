<?php

namespace Wladimir\ParserExcel\AST\Operator;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class Operator implements Expression
{
    public function __construct(
        protected Token $token,
        protected Expression $leftExpression,
        protected Expression $rightExpression,
    ) {
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $right = $this->rightExpression->calculate($repository);

        switch ($this->token->value) {
            case '/':
                return $this->calculateDivide($left, $right);

            case '*':
                return $this->calculateMultiple($left, $right);

            case '-':
                return $this->calculateMinus($left, $right);

            case '+':
                return $this->calculatePlus($left, $right);

            case '^':
                return $this->calculateToPower($left, $right);

            case '=':
                return $this->calculateEqual($left, $right);

            case '>':
                return $this->calculateMore($left, $right);

            case '<':
                return $this->calculateMore($left, $right);
        }

        throw new UnsupportedError('Неизвестный оператор: ' . $this->token->value);
    }

    public function getUnsupportedError(mixed $leftValue, mixed $rightValue): UnsupportedError
    {
        $leftType = \gettype($leftValue);
        $rightType = \gettype($rightValue);

        return new UnsupportedError("Неподдерживаемые типы операндов  {$leftType} {$this->token->value} {$rightType}");
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type'            => 'operator',
            'leftExpression'  => $this->leftExpression,
            'rightExpression' => $this->leftExpression,
        ];
    }

    public function calculateDivide(mixed $left, mixed $right): mixed
    {
        if (is_numeric($left) && is_numeric($right)) {
            return $left / $right;
        }
        throw $this->getUnsupportedError($left, $right);
    }

    public function calculateMultiple(mixed $left, mixed $right): mixed
    {
        if (is_numeric($left) && is_numeric($right)) {
            return $left * $right;
        }
        throw $this->getUnsupportedError($left, $right);
    }

    public function calculateMinus(mixed $left, mixed $right): mixed
    {
        if (is_numeric($left) && is_numeric($right)) {
            return $left - $right;
        }
        throw $this->getUnsupportedError($left, $right);
    }

    public function calculatePlus(mixed $left, mixed $right): mixed
    {
        if (is_numeric($left) && is_numeric($right)) {
            return $left + $right;
        }

        return $left . $right;
    }

    public function calculateToPower(mixed $left, mixed $right): mixed
    {
        if (is_numeric($left) && is_numeric($right)) {
            return $left ** $right;
        }
        throw $this->getUnsupportedError($left, $right);
    }

    public function calculateEqual($left, $riht): bool
    {
        return $left == $riht;
    }

    public function calculateMore($left, $riht): bool
    {
        return $left > $riht;
    }

    public function calculateLess($left, $riht): bool
    {
        return $left < $riht;
    }
}
