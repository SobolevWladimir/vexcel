<?php

declare(strict_types=1);

namespace Tests\unit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SobolevWladimir\Vexcel\Lexer\Lexer;
use SobolevWladimir\Vexcel\Lexer\Token;
use SobolevWladimir\Vexcel\Lexer\TokenType;

final class LexerTest extends TestCase
{
    public function testString(): void
    {
        $formula = "'СУММА(2;3.3)'";
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::String, 'СУММА(2;3.3)'),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($tokens), json_encode($response));
    }

    public function testInt(): void
    {
        $formula = '3';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Int, 3),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($tokens), json_encode($response));
    }

    public function testFloat(): void
    {
        $formula = '3.10';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Float, 3.10),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($tokens), json_encode($response));
    }

    public function testOperatorMore(): void
    {
        $formula = '3>4';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Int, 3),
            new Token(TokenType::ConditionalOperator, '>', 0, 1),
            new Token(TokenType::Int, 4, 0, 2),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorMoreSpace(): void
    {
        $formula = '3 > 4';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Int, 3),
            new Token(TokenType::ConditionalOperator, '>', 0, 2),
            new Token(TokenType::Int, 4, 0, 4),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorMoreEqual(): void
    {
        $formula = '3 >= 4';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Int, 3),
            new Token(TokenType::ConditionalOperator, '>=', 0, 2),
            new Token(TokenType::Int, 4, 0, 5),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorLessEqual(): void
    {
        $formula = '3 <= 4';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Int, 3),
            new Token(TokenType::ConditionalOperator, '<=', 0, 2),
            new Token(TokenType::Int, 4, 0, 5),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testVar(): void
    {
        $formula = 'Месторождение + Language';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Variable, 'Месторождение'),
            new Token(TokenType::BinaryOperator, '+', 0, 14),
            new Token(TokenType::Variable, 'Language', 0, 16),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testSpatialVar(): void
    {
        $formula = '$Месторождение Language$ + Вася';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Variable, 'Месторождение Language'),
            new Token(TokenType::BinaryOperator, '+', 0, 25),
            new Token(TokenType::Variable, 'Вася', 0, 27),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testSpatialTwo(): void
    {
        $formula = '\Месторождение Language\ + Вася';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Variable, 'Месторождение Language'),
            new Token(TokenType::BinaryOperator, '+', 0, 25),
            new Token(TokenType::Variable, 'Вася', 0, 27),
        ];
        $tokens = $sut->getAllTokens();
        self::assertSame(json_encode($response), json_encode($tokens));
    }

    public function testVarLink(): void
    {
        $formula = 'Пользователь.Имя';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Variable, 'Пользователь.Имя'),
        ];
        $tokens = $sut->getAllTokens();
        $this->assertSameTokens($response, $tokens);
    }

    public function testVarNubmer(): void
    {
        $formula = 'Пользователь_123';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Variable, 'Пользователь_123'),
        ];
        $tokens = $sut->getAllTokens();
        $this->assertSameTokens($response, $tokens);
    }

    public function testFunctionSumm(): void
    {
        $formula = 'Сумма(2;3)';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Function, 'Сумма'),
            new Token(TokenType::Int, 2, 0, 6),
            new Token(TokenType::Separator, ';', 0, 7),
            new Token(TokenType::Int, 3, 0, 8),
            new Token(TokenType::Parentheses, ')', 0, 9),
        ];
        $tokens = $sut->getAllTokens();
        $this->assertSameTokens($response, $tokens);
    }

    public function testGroupSumm(): void
    {
        $formula = '(2+3)-2';
        $sut = new Lexer();
        $sut->setCode($formula);
        $response = [
            new Token(TokenType::Parentheses, '(', 0, 0),
            new Token(TokenType::Int, 2, 0, 1),
            new Token(TokenType::BinaryOperator, '+', 0, 2),
            new Token(TokenType::Int, 3, 0, 3),
            new Token(TokenType::Parentheses, ')', 0, 4),
            new Token(TokenType::BinaryOperator, '-', 0, 5),
            new Token(TokenType::Int, 2, 0, 6),
        ];
        $tokens = $sut->getAllTokens();
        $this->assertSameTokens($response, $tokens);
    }

    public function testFromFile(): void
    {
        $formula = file_get_contents(__DIR__ . '/formula.txt');
        $sut = new Lexer();
        $sut->setCode((string)$formula);
        $response = [
            new Token(TokenType::Function, 'Если'),
            new Token(TokenType::Function, 'Сумма', 1, 4),
            new Token(TokenType::Variable, 'Пользователь', 1, 10),
            new Token(TokenType::Separator, ';', 1, 22),
            new Token(TokenType::Int, 3, 1, 24),
            new Token(TokenType::Parentheses, ')', 1, 25),
            new Token(TokenType::ConditionalOperator, '==', 1, 27),
            new Token(TokenType::Int, 5, 1, 30),
            new Token(TokenType::Separator, ';', 1, 31),
            new Token(TokenType::String, 'Да', 2, 4),
            new Token(TokenType::Separator, ';', 2, 8),
            new Token(TokenType::Float, 3.3, 3, 4),
            new Token(TokenType::Parentheses, ')', 4, 0),
        ];
        $tokens = $sut->getAllTokens();
        $this->assertSameTokens($response, $tokens);
    }

    /**
     * @param Token[] $expectedValues
     * @param Token[] $actual
     *
     * @throws ExpectationFailedException
     */
    private function assertSameTokens(array $expectedValues, array $actual): void
    {
        self::assertSame(\count($expectedValues), \count($actual));

        foreach ($actual as $key => $token) {
            $expected = $expectedValues[$key];
            self::assertSame($expected->type, $token->type);
            self::assertSame($expected->value, $token->value);
            $value = $token->value;
            self::assertSame($expected->row, $token->row, " invalid row! value: '{$value}'");
            self::assertSame($expected->column, $token->column, "invalid column! value: '{$value}'");
        }
    }
}
