<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Data\DummyValueRepository;
use Wladimir\ParserExcel\AST\DataType\FloatExpression;
use Wladimir\ParserExcel\AST\DataType\IntExpression;
use Wladimir\ParserExcel\AST\DataType\StringExpression;
use Wladimir\ParserExcel\AST\Operator\Binary\MinusOperator;
use Wladimir\ParserExcel\Exceptions\UnsupportedError;

final class MinusOperatorTest extends TestCase
{
    public function testInt(): void
    {
        $left = new IntExpression(8);
        $rigth = new IntExpression(2);
        $repository  = new DummyValueRepository();
        $sut = new MinusOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, 6);
    }

    public function testFloat(): void
    {
        $left = new FloatExpression(8.0);
        $rigth = new FloatExpression(2.0);
        $repository  = new DummyValueRepository();
        $sut = new MinusOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, 6.0);
    }
    public function testString(): void
    {
        $left = new StringExpression("two");
        $rigth = new StringExpression("two");
        $repository  = new DummyValueRepository();
        $sut = new MinusOperator($left, $rigth);
        $this->expectException(UnsupportedError::class);
        $sut->calculate($repository);
    }
}
