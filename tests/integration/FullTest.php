<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Data\ValueRepositoryFake;
use Wladimir\ParserExcel\Parser\Parser;

final class FullTest extends TestCase
{
    /**
     * @return array<int,mixed>
     */
    public static function notVariableProvider(): array
    {
        return [
            ['3', 3],
            ['3+3', 6],
            ['3-3', 0],
            ['3/3', 1],
            ['3*3', 9],
            ['"test"', 'test'],
            ['3+"test"', '3test'],
            ['3*(2+2)', 12],
            ['3*(2+2+(1*1))', 15],
            ['2+2*3', 8],
            ['ОКРУГЛВВЕРХ(2.25; 1)', 2.3],
            ['ОКРУГЛВНИЗ(2.25; 1)', 2.2],
            ['ЕСЛИ(ИСТИНА();"ДА";"НЕТ")', 'ДА'],
            // ['ЕСЛИ(ЛОЖЬ();"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(2=2;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(2=2;3+5;3-2)', 8],
            ['ЕСЛИ(2=3;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(2=3;3+5;3-2)', 1],
            ['ЕСЛИ(2>1;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(2>3;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(1<2;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(3<2;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(НЕ(3<2);"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(НЕ(3>2);"ДА";"НЕТ")', 'НЕТ'],
        ];
    }

    /**
     * @return array<int,mixed>
     */
    public static function variableProvider(): array
    {
        return [
            ['ТРИ', 3],
            ['ТРИ+3', 6],
            ['ТРИ-3', 0],
            ['ТРИ/3', 1],
            ['ТРИ*3', 9],
            ['ТРИ+"test"', '3test'],
            ['ТРИ*(ДВА+2)', 12],
            ['ТРИ*(ДВА+2+(1*1))', 15],
            ['ДВА+2*ТРИ', 8],
            ['ЕСЛИ(ДВА=2;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(ДВА=2;3+5;3-2)', 8],
            ['ЕСЛИ(ДВА=ТРИ;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(ДВА=3;3+5;3-2)', 1],
            ['ЕСЛИ(ДВА>ОДИН;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(ДВА>3;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(ОДИН<ДВА;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(ТРИ<ДВА;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(НЕ(ТРИ<ДВА);"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(НЕ(ТРИ>ДВА);"ДА";"НЕТ")', 'НЕТ'],
        ];
    }

    #[DataProvider('notVariableProvider')]
    public function testNotVaiableProvider(string $code, mixed $expected): void
    {
        $sut = new Parser();
        $formula = $sut->parse($code);
        self::assertSame($expected, $formula->calculate());
    }

    #[DataProvider('variableProvider')]
    public function testVariableProvider(string $code, mixed $expected): void
    {
        $sut = new Parser();
        $formula = $sut->parse($code);
        $repository = new ValueRepositoryFake();
        self::assertSame($expected, $formula->calculate($repository));
    }
}
