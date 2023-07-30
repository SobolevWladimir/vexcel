<?php

namespace Tests\Data;

use Wladimir\ParserExcel\Lexer\Lexer;
use Wladimir\ParserExcel\Lexer\Token;

class FakeLexer extends Lexer
{
    /**
     * @param Token[] $tokens
     * @return void
     */
    public function __construct(private array $tokens)
    {
    }

    /**
     * @return Token[]
     */
    public function getAllTokens(): array
    {
        return $this->tokens;
    }
}
