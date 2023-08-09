<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Wladimir\ParserExcel\Parser\Parser;

final class FullTest extends TestCase
{
    public function testInt(): void
    {
        $sut = new Parser();
        $formula = $sut->parse('3');
        self::assertSame($formula->calculate(), 3);
    }
}
