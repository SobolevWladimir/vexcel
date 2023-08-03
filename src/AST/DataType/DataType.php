<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\Lexer\Token;

abstract class DataType implements Expression
{
    public function __construct(protected Token $token)
    {
    }
}
