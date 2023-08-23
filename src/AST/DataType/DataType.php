<?php

namespace SobolevWladimir\Vexcel\AST\DataType;

use SobolevWladimir\Vexcel\AST\Expression;
use SobolevWladimir\Vexcel\Lexer\Token;

abstract class DataType implements Expression
{
    public function __construct(protected Token $token)
    {
    }

    public function getToken(): Token
    {
        return $this->token;
    }
}
