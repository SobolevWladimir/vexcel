<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Data\FakeLexer;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Lexer\TokenType;
use Wladimir\ParserExcel\Parser\Parser;

final class ParserTest extends TestCase
{
    public function testInt(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Int, 3),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 3);
    }

    public function testFloat(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 3);
    }

    public function testSumm(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, "+"),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 6);
    }

    public function testMinus(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, "-"),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 0);
    }

    public function testDevide(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, "/"),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 1);
    }

    public function testMultiple(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, "*"),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 9);
    }

    public function testPov(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, "^"),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), 27);
    }

    public function testParenthees(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 2.0),
            new Token(TokenType::BinaryOperator, "*"),
            new Token(TokenType::Parentheses, "("),
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, "+"),
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::Parentheses, ")"),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");
        $this->assertSame($formula->calculate(), 12);
    }

    public function testConditionTrue(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::ConditionalOperator, "="),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), true);
    }

    public function testConditionFalse(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 2.0),
            new Token(TokenType::ConditionalOperator, "="),
            new Token(TokenType::Float, 3.0),
         ]);
        $sut  = new Parser(lexer: $lexer);
        $formula = $sut->parse("");

        $this->assertSame($formula->calculate(), false);
    }
}
