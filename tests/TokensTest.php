<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Wladimir\ParserExcel\Token\Token;
use Wladimir\ParserExcel\Token\TokenValue;
use Wladimir\ParserExcel\Token\ValueType;

final class TokensTest extends TestCase
{
    public function testString(): void
    {
         $formula  = "'СУММА(2;3.3)'";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::String, 'СУММА(2;3.3)'),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($tokens), json_encode($response));
    }

    public function testInt(): void
    {
         $formula  = "3";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($tokens), json_encode($response));
    }

    public function testFloat(): void
    {
         $formula  = "3.10";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Float, 3.10),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($tokens), json_encode($response));
    }

    public function testOperatorMore(): void
    {
         $formula  = "3>4";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
            new TokenValue(ValueType::ConditionalOperator, '>'),
            new TokenValue(ValueType::Int, 4),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorMoreSpace(): void
    {
         $formula  = "3 > 4";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
            new TokenValue(ValueType::ConditionalOperator, '>'),
            new TokenValue(ValueType::Int, 4),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorMoreEqual(): void
    {
         $formula  = "3 >= 4";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
            new TokenValue(ValueType::ConditionalOperator, '>='),
            new TokenValue(ValueType::Int, 4),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorLessEqual(): void
    {
         $formula  = "3 <= 4";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
            new TokenValue(ValueType::ConditionalOperator, '<='),
            new TokenValue(ValueType::Int, 4),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testOperatorNotEqual(): void
    {
         $formula  = "3 != 4";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
            new TokenValue(ValueType::ConditionalOperator, '!='),
            new TokenValue(ValueType::Int, 4),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testVar(): void
    {
         $formula  = "Месторождение + Language";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Variable, "Месторождение"),
            new TokenValue(ValueType::Operator, "+"),
            new TokenValue(ValueType::Variable, "Language"),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testVarLink(): void
    {
         $formula  = "Пользователь.Имя";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Variable, "Пользователь.Имя"),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }

    public function testFunctionSumm(): void
    {
         $formula  = "Сумма(2;3)";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Function, "Сумма"),
            new TokenValue(ValueType::Int, 2),
            new TokenValue(ValueType::Separator, ";"),
            new TokenValue(ValueType::Int, 3),
            new TokenValue(ValueType::EndFunction, ')'),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }


    public function testFromFile(): void
    {
         $formula  = file_get_contents(__DIR__ . "/formula.txt");
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Function, "Если"),
            new TokenValue(ValueType::Function, "Сумма", 1, 4),
            new TokenValue(ValueType::Variable, "Пользователь", 1, 10),
            new TokenValue(ValueType::Separator, ";", 1, 22),
            new TokenValue(ValueType::Int, 3, 1, 24),
            new TokenValue(ValueType::EndFunction, ')', 1, 25),
            new TokenValue(ValueType::ConditionalOperator, '==', 1, 27),
            new TokenValue(ValueType::Int, 5, 1, 30),
            new TokenValue(ValueType::Separator, ";", 1, 31),
            new TokenValue(ValueType::String, "Да", 2, 4),
            new TokenValue(ValueType::Separator, ";", 2, 8),
            new TokenValue(ValueType::Float, 3.3, 3, 4),
            new TokenValue(ValueType::EndFunction, ')', 4, 0),
         ];
         $tokens  = $sut->getAll();
         $this->assertSame(count($response), count($tokens));
         foreach ($tokens as $key => $token) {
             $expected = $response[$key];
             $this->assertSame($expected->type, $token->type);
             $this->assertSame($expected->value, $token->value);
             $value = $token->value;
             $this->assertSame($expected->row, $token->row, "value: $value");
             $this->assertSame($expected->column, $token->column, "value: $value");
         }
    }
}