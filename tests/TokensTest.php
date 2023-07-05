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
         $tokens  = $sut->getTokens();
         $this->assertSame(json_encode($tokens), json_encode($response));
    }

    public function testInt(): void
    {
         $formula  = "3";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Int, 3),
         ];
         $tokens  = $sut->getTokens();
         $this->assertSame(json_encode($tokens), json_encode($response));
    }

    public function testFloat(): void
    {
         $formula  = "3.10";
         $sut = new Token($formula);
         $response = [
            new TokenValue(ValueType::Float, 3.10),
         ];
         $tokens  = $sut->getTokens();
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
         $tokens  = $sut->getTokens();
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
         $tokens  = $sut->getTokens();
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
         $tokens  = $sut->getTokens();
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
         $tokens  = $sut->getTokens();
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
         $tokens  = $sut->getTokens();
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
         $tokens  = $sut->getTokens();
         $this->assertSame(json_encode($response), json_encode($tokens));
    }
}
