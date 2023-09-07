<?php

declare(strict_types=1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use SobolevWladimir\Vexcel\Lexer\Token;
use SobolevWladimir\Vexcel\Lexer\TokenType;
use SobolevWladimir\Vexcel\Parser\Parser;
use Tests\Data\FakeLexer;

final class ParserTest extends TestCase
{
    public function testInt(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Int, 3),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 3);
    }

    public function testFloat(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 3.0);
    }

    public function testSumm(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '+'),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 6.0);
    }

    public function testMinus(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '-'),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 0.0);
    }

    public function testDevide(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '/'),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 1.0);
    }

    public function testMultiple(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '*'),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 9.0);
    }

    public function testPov(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '^'),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 27.0);
    }

    public function testParenthees(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 2.0),
            new Token(TokenType::BinaryOperator, '*'),
            new Token(TokenType::Parentheses, '('),
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '+'),
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::Parentheses, ')'),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');
        self::assertSame($formula->calculate(), 12.0);
    }

    public function testParentheesTwo(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Parentheses, '('),
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::BinaryOperator, '+'),
            new Token(TokenType::Float, 4.0),
            new Token(TokenType::Parentheses, ')'),
            new Token(TokenType::BinaryOperator, '*'),
            new Token(TokenType::Float, 2.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');
        self::assertSame($formula->calculate(), 14.0);
    }

    public function testConditionTrue(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 3.0),
            new Token(TokenType::ConditionalOperator, '='),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), true);
    }

    public function testConditionFalse(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Float, 2.0),
            new Token(TokenType::ConditionalOperator, '='),
            new Token(TokenType::Float, 3.0),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), false);
    }
}
