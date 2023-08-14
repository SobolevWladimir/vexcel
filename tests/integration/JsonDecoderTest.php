<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Data\ValueRepositoryFake;
use Wladimir\ParserExcel\AST\FormulaAST;
use Wladimir\ParserExcel\Parser\Parser;

class JsonDecoderTest extends TestCase
{
    /**
     * @return array<int,mixed>
     */
    public static function variableProvider(): array
    {
        return [
            ['ЕСЛИ(ОДИН<ДВА;"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(ТРИ<ДВА;"ДА";"НЕТ")', 'НЕТ'],
            ['ЕСЛИ(НЕ(ТРИ<ДВА);"ДА";"НЕТ")', 'ДА'],
            ['ЕСЛИ(НЕ(ТРИ>ДВА);"ДА";"НЕТ")', 'НЕТ'],
        ];
    }

    #[DataProvider('variableProvider')]
    public function testVariableProvider(string $code, mixed $expected): void
    {
        $parser = new Parser();
        $formula = $parser->parse($code);
        $repository = new ValueRepositoryFake();
        $formulaJson = json_encode($formula);
        $sut = FormulaAST::fromJson(json_decode((string)$formulaJson, true));

        self::assertSame($expected, $sut->calculate($repository));
    }
}
