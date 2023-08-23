<?php

namespace Tests\Data;

use SobolevWladimir\Vexcel\Lexer\Lexer;
use SobolevWladimir\Vexcel\Lexer\Token;

class FakeLexer extends Lexer
{
    /**
     * @param Token[] $tokens
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
