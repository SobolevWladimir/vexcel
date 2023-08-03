<?php

namespace Wladimir\ParserExcel\AST\Operator;

use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class Operator implements Expression
{
    const HANDLERS = [
      '/' => $this->calculateDivide,
      '*' => $this->calculateMultiple,
      '-' => $this->calculateMinus,
      '+' => $this->calculatePlus,
      '^' => $this->calculatePlus,
    ];
    public function __construct(
        protected Expression $leftExpression,
        protected Expression $rightExpression,
        protected Token $token,
    ) {
    }

    public function calculate(ValueRepositoryInterface $repository): mixed
    {
        if (!array_key_exists($this->token->value, self::HANDLERS)) {
            return new  UnsupportedError("Неизвестный оператор: " . $this->token->value);
        }
        $handler  = self::HANDLERS[$this->token->value];
        return $handler($repository);
    }


    public function getUnsupportedError(mixed $leftValue, mixed $rightValue): UnsupportedError
    {
        $leftType  = gettype($leftValue);
        $rightType  = gettype($rightValue);
        return new  UnsupportedError("Неподдерживаемые типы операндов  $leftType {$this->token->value} $rightType");
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => 'operator',
        'leftExpression' => $this->leftExpression,
        'rightExpression' => $this->leftExpression,
        ];
    }

    public function calculateDivide(ValueRepositoryInterface $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return $left / $riht;
        }
        throw $this->getUnsupportedError($left, $riht);
    }

    public function calculateMultiple(ValueRepositoryInterface $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return $left * $riht;
        }
        throw $this->getUnsupportedError($left, $riht);
    }

    public function calculateMinus(ValueRepositoryInterface $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return $left - $riht;
        }
        throw $this->getUnsupportedError($left, $riht);
    }

    public function calculatePlus(ValueRepositoryInterface $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return $left + $riht;
        }
        return $left . $riht;
    }

    public function calculateToPower(ValueRepositoryInterface $repository): mixed
    {
        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        if (is_numeric($left) && is_numeric($riht)) {
            return pow($left, $riht);
        }
        throw $this->getUnsupportedError($left, $riht);
    }
}
