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
    // public function testsimpleParse()
    // {
        // $formula  = "СУММА(2;3.3)";
        // $sut = new Tokens($formula);
        // $response = [
        // [
        // 'type' => 'function',
        // 'value' => 'СУММА',
        // ],
        // [
        // 'type' => 'number',
        // 'value' => 2,
        // ],
        // [
        // 'type' => 'number',
        // 'value' => 3.3
        // ]

        // ];
        // $this->assertSame($sut->getTokens(), $response);
    // }
}
