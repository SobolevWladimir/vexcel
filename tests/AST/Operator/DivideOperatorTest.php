<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Data\DummyVariableRepository;
use Wladimir\ParserExcel\AST\DataType\FloatExpression;
use Wladimir\ParserExcel\AST\DataType\IntExpression;
use Wladimir\ParserExcel\AST\Operator\DivideOperator;

final class DivideOperatorTest extends TestCase
{
    public function testInt(): void
    {
        $left = new IntExpression(4);
        $rigth = new IntExpression(2);
        $repository  = new DummyVariableRepository();
        $sut = new DivideOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, 2);
    }


    public function testFloat(): void
    {
        $left = new FloatExpression(4.0);
        $rigth = new FloatExpression(2.0);
        $repository  = new DummyVariableRepository();
        $sut = new DivideOperator($left, $rigth);
        $value  = $sut->calculate($repository);
        $this->assertSame($value, 2.0);
    }
}
