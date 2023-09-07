<?php

namespace SobolevWladimir\Vexcel\AST\Function;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\AST\Expression;
use SobolevWladimir\Vexcel\Exceptions\SyntaxError;
use SobolevWladimir\Vexcel\Lexer\Token;

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

    /**
     * @return Expression[]
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    public function getUsedVariables(): array
    {
        $result = [];

        foreach ($this->args as $arg) {
            $result = array_merge($result, $arg->getUsedVariables());
        }

        return $result;
    }
}
