<?php

namespace Wladimir\ParserExcel;

use Wladimir\ParserExcel\AST\FunctionAST;

interface ParserInterface
{
    public function parse(string $code): FunctionAST;
}
