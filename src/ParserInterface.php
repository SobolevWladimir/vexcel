<?php

namespace SobolevWladimir\Vexcel;

use SobolevWladimir\Vexcel\AST\FormulaAST;

interface ParserInterface
{
    public function parse(string $code): ?FormulaAST;
}
