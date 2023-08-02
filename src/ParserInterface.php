<?php

namespace Wladimir\ParserExcel;

use Wladimir\ParserExcel\AST\FormulaAST;

interface ParserInterface
{
    public function parse(string $code): ?FormulaAST;
}
