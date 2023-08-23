<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SobolevWladimir\Vexcel\AST\FormulaAST;
use SobolevWladimir\Vexcel\Parser\Parser;

class VexcelTest extends TestCase
{
    /**
     * @return array<int,mixed>
     */
    public static function variableProvider(): array
    {
        return [
            ['1', '1'],
            ['ОДИН', 'ОДИН'],
            ['1.2', '1.2'],
            ['"string"', '"string"'],
            ['ЕСЛИ(ОДИН<ДВА;"ДА";"НЕТ")', 'ЕСЛИ( ОДИН < ДВА; "ДА"; "НЕТ")'],
            ['ЕСЛИ(НЕ(3>2);"ДА";"НЕТ")', 'ЕСЛИ( НЕ( 3 > 2); "ДА"; "НЕТ")'],
        ];
    }

    #[DataProvider('variableProvider')]
    public function testVariableProvider(string $code, mixed $expected): void
    {
        $parser = new Parser();
        $formula = $parser->parse($code);
        $formulaJson = json_encode($formula);
        $sut = FormulaAST::fromJson(json_decode((string)$formulaJson, true));

        self::assertSame($expected, $sut->toCode());
    }
}
