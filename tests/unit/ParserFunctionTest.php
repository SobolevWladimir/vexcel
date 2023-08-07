<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Data\FakeLexer;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Lexer\TokenType;
use Wladimir\ParserExcel\Parser\Parser;

final class ParserFunctionTest extends TestCase
{
    public function testIfInt(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Function, "ЕСЛИ"),
            new Token(TokenType::Int, 1),
            new Token(TokenType::Separator, ";"),
            new Token(TokenType::Int, 3),
            new Token(TokenType::Separator, ";"),
            new Token(TokenType::Int, 2),
            new Token(TokenType::Parentheses, ")"),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 3);
    }
}