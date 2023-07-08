<?php

namespace Wladimir\ParserExcel\Exceptions;

use Exception;

class SyntaxError extends Exception
{
    public function __construct(string $message, int $code, private int $row, private int $column)
    {
        parent::__construct($message, $code);
    }


    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function __toString(): string
    {
        return "Синтаксическая ошибка! {$this->getMessage()}. строка: {$this->row}, колонка: {$this->column}";
    }
}
