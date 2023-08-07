<?php

namespace Wladimir\ParserExcel\Exceptions;

use Wladimir\ParserExcel\Lexer\Token;

class SyntaxError extends \Exception
{
    public function __construct(string $message, private Token $token, int $code = 400)
    {
        $mess = "Синтаксическая ошибка! {$message}. строка: {$this->token->row}, колонка: {$this->token->column}";
        parent::__construct($mess, $code);
    }

    public function getRow(): int
    {
        return $this->token->row;
    }

    public function getColumn(): int
    {
        return $this->token->column;
    }
}
