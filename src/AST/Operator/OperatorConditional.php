<?php

namespace Wladimir\ParserExcel\AST\Operator;

use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class OperatorConditional implements Expression
{
    const HANDLERS = [
      '=' => $this->calculateEqual,
      '>' => $this->calculateMore,
      '<' => $this->calculateLess,
    ];
    public function __construct(
        protected Expression $leftExpression,
        protected Expression $rightExpression,
        protected Token $token,
    ) {
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        if (!array_key_exists($this->token->value, self::HANDLERS)) {
            return new  UnsupportedError("Неизвестный оператор: " . $this->token->value);
        }

        $left = $this->leftExpression->calculate($repository);
        $riht = $this->rightExpression->calculate($repository);
        $handler  = self::HANDLERS[$this->token->value];
        return $handler($left, $riht);
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

    public function calculateEqual($left, $riht): mixed
    {
        return $left  == $riht;
    }

    public function calculateMore($left, $riht): mixed
    {
        return $left  > $riht;
    }

    public function calculateLess($left, $riht): mixed
    {
        return $left  < $riht;
    }
}
