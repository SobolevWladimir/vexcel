<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Data\DummyVariableRepository;
use Wladimir\ParserExcel\AST\DataType\FloatExpression;
use Wladimir\ParserExcel\AST\DataType\IntExpression;
use Wladimir\ParserExcel\AST\DataType\StringExpression;
use Wladimir\ParserExcel\AST\Operator\Binary\PlusOperator;

final class PlusOperatorTest extends TestCase
{
    public function testInt(): void
    {
        $left = new IntExpression(4);
        $rigth = new IntExpression(2);
        $repository  = new DummyVariableRepository();
        $sut = new PlusOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, 6);
    }


    public function testFloat(): void
    {
        $left = new FloatExpression(4.0);
        $rigth = new FloatExpression(2.0);
        $repository  = new DummyVariableRepository();
        $sut = new PlusOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, 6.0);
    }
    public function testString(): void
    {
        $left = new StringExpression("two");
        $rigth = new StringExpression("two");
        $repository  = new DummyVariableRepository();
        $sut = new PlusOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, "twotwo");
    }
}
