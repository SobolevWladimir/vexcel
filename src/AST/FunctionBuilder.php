<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\AST\Function\AbstractFunction;
use Wladimir\ParserExcel\AST\Function\Funif;
use Wladimir\ParserExcel\Exceptions\SyntaxError;
use Wladimir\ParserExcel\Lexer\Token;

class FunctionBuilder
{
    public function build(Token $token, array $args): AbstractFunction
    {
        if ($token->value == 'ЕСЛИ') {
            return new Funif($token, $args);
        }
        throw new SyntaxError('Неизвестная функция: ' . $token->value, $token);
    }
}
