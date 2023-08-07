<?php

namespace Wladimir\ParserExcel\AST\Function;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Exceptions\SyntaxError;
use Wladimir\ParserExcel\Lexer\Token;

abstract class AbstractFunction implements Expression
{
    /**
     * @param Token $token
     * @param Expression[] $args
     * @return void
     */
    public function __construct(
        protected Token $token,
        protected array $args,
    ) {
        if (count($args) < $this->getNumberArguments()) {
            throw new SyntaxError('Количество аргументов меньше чем ' . $this->getNumberArguments(), $token);
        }
        if (count($args) > $this->getNumberArguments()) {
            throw new SyntaxError('Количество аргументов болльше чем ' . $this->getNumberArguments(), $token);
        }
    }


  /**
 * Количество аргументов принимаемой функцией
 * @return int  */
    abstract protected function getNumberArguments(): int;
}
