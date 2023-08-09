<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Wladimir\ParserExcel\Parser\Parser;

final class FullTest extends TestCase
{
    /**
     * @return array<int,mixed>
     */
    public static function additionProvider(): array
    {
        return [
            ['3', 3],
            ['3+3', 6],
            ['"test"', 'test'],
            ['3+"test"', '3test'],
            ['3*(2+2)', 12],
            ['2+2*3', 8],
            ['ЕСЛИ(2=2;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(2=2;3+5;3-2)', 8],
            ['ЕСЛИ(2=3;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(2=3;3+5;3-2)', 1],
            ['ЕСЛИ(2>1;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(2>3;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(1<2;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(3<2;"ДА";"НЕТ")', 'НЕТ'],
        ];
    }

    #[DataProvider('additionProvider')]
    public function testProvider(string $code, mixed $expected): void
    {
        $sut = new Parser();
        $formula = $sut->parse($code);
        self::assertSame($expected, $formula->calculate());
    }
}
