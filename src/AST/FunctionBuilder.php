<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\AST\Function\AbstractFunction;
use Wladimir\ParserExcel\AST\Function\Funif;
use Wladimir\ParserExcel\Exceptions\SyntaxError;
use Wladimir\ParserExcel\Lexer\Token;

class FunctionBuilder
{
    /**
     * @param Token        $token
     * @param Expression[] $args
     *
     * @return AbstractFunction
     *
     * @throws SyntaxError
     */
    public function build(Token $token, array $args): AbstractFunction
    {
        if ($token->value == 'ЕСЛИ') {
            return new Funif($token, $args);
        }
        //НЕ
        //Округлить
        //ИЛИ
    //ЛОЖЬ Возвращает логическое значение ЛОЖЬ.
    //ИСТИНА - Возвращает логическое значение ИСТИНА.
        throw new SyntaxError('Неизвестная функция: ' . $token->value, $token);
    }
}
