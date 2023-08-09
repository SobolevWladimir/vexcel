<?php

namespace Wladimir\ParserExcel\Exceptions;

use Wladimir\ParserExcel\Lexer\Token;

class SyntaxError extends \Exception
{
    public function __construct(string $message, private ?Token $token = null, int $code = 400)
    {
        $row = 0;
        $column = 0;

        if ($this->token !== null) {
            $row = $this->token->row;
            $column = $this->token->column;
        }
        $mess = "Синтаксическая ошибка! {$message}. строка: {$row}, колонка: {$column}";
        parent::__construct($mess, $code);
    }

    public function getRow(): int
    {
        if ($this->token !== null) {
            return $this->token->row;
        }

        return 0;
    }

    public function getColumn(): int
    {
        if ($this->token !== null) {
            return $this->token->column;
        }

        return 0;
    }
}
