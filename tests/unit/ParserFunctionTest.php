<?php

declare(strict_types=1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use SobolevWladimir\Vexcel\Lexer\Token;
use SobolevWladimir\Vexcel\Lexer\TokenType;
use SobolevWladimir\Vexcel\Parser\Parser;
use Tests\Data\FakeLexer;

final class ParserFunctionTest extends TestCase
{
    public function testIfInt(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Function, 'ЕСЛИ'),
            new Token(TokenType::Int, 1),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 3),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 2),
            new Token(TokenType::Parentheses, ')'),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 3);
    }

    public function testIfFalse(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Function, 'ЕСЛИ'),
            new Token(TokenType::Int, 0),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 3),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 2),
            new Token(TokenType::Parentheses, ')'),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 2);
    }

    public function testIfWithSumm(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Function, 'ЕСЛИ'),
            new Token(TokenType::Int, 0),
            new Token(TokenType::BinaryOperator, '+'),
            new Token(TokenType::Int, 1),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 3),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 2),
            new Token(TokenType::Parentheses, ')'),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 3);
    }

    public function testIfWithEqual(): void
    {
        $lexer = new FakeLexer([
            new Token(TokenType::Function, 'ЕСЛИ'),
            new Token(TokenType::Int, 0),
            new Token(TokenType::ConditionalOperator, '='),
            new Token(TokenType::Int, 1),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 3),
            new Token(TokenType::Separator, ';'),
            new Token(TokenType::Int, 2),
            new Token(TokenType::Parentheses, ')'),
        ]);
        $sut = new Parser(lexer: $lexer);
        $formula = $sut->parse('');

        self::assertSame($formula->calculate(), 2);
    }
}
