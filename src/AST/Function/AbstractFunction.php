<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Exceptions\SyntaxError;
use Wladimir\ParserExcel\Lexer\Token;

abstract class AbstractFunction implements Expression
{
    /**
     * @param Token        $token
     * @param Expression[] $args
     */
    public function __construct(
        protected Token $token,
        protected array $args,
    ) {
        if (\count($args) < $this->getNumberArguments()) {
            throw new SyntaxError(
                'Количество аргументов меньше чем ' . $this->getNumberArguments() . ' Функция:' . $token->value,
                $token
            );
        }

        if (\count($args) > $this->getNumberArguments()) {
            throw new SyntaxError(
                'Количество аргументов болльше чем ' . $this->getNumberArguments() . ' Функция:' . $token->value,
                $token
            );
        }
    }

    public function getJsonData(): JsonData
    {
        $args = [];

        foreach ($this->args as $arg) {
            $args[] = $arg->getJsonData();
        }

        return new JsonData('function', $this->token, ['args' => $args]);
    }

    /**
     * Количество аргументов принимаемой функцией.
     *
     * @return int  */
    abstract protected function getNumberArguments(): int;

    public function getToken(): Token
    {
        return $this->token;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
