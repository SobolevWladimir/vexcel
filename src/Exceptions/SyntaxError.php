<?php

namespace Wladimir\ParserExcel\Exceptions;

use Exception;

class SyntaxError extends Exception
{
    public function __construct(string $message, int $code, private int $row, private int $column)
    {
        $mess  = "Синтаксическая ошибка! $message. строка: {$this->row}, колонка: {$this->column}";
        parent::__construct($mess, $code);
    }


    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
