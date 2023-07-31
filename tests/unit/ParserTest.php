<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Data\DummyVariableRepository;
use Tests\Data\FakeLexer;
use Wladimir\ParserExcel\AST\FunctionAST;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Lexer\TokenType;
use Wladimir\ParserExcel\Parser\Parser;

final class ParserTest extends TestCase
{
    /* x+y*z  = “x+(y*z)”*/
  // “x+(y*z)”
  // “a+b+(c+d)*e*f+g”.

    // public function testParse()
    // {
    //     $this->assertSame('2', '3');
    // }
    public function testInt(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Int, 3),
         ]);
        $sut  = new Parser(null, $lexer);
        $sut->parse("");
        $this->assertSame(1, 1);
    }
}
