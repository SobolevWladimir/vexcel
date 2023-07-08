<?php

namespace Wladimir\ParserExcel\Exceptions;

use Exception;
use Wladimir\ParserExcel\Token\TokenValue;

class SyntaxError extends Exception
{
    public function __construct(string $message, int $code, TokenValue $token)
    {
        parent::__construct($message, $code);
    }
}
