<?php

namespace Wladimir\ParserExcel\Parser;

use Wladimir\ParserExcel\AST\FunctionAST;
use Wladimir\ParserExcel\Lexer\Lexer;
use Wladimir\ParserExcel\ParserInterface;
use Wladimir\ParserExcel\Repository\VariableRepository;

class Parser implements ParserInterface
{
    public function __construct(
        private VariableRepository $repository,
        private ?Lexer $lexer = null,
    ) {
    }
    /**
     * @param string $code
     * @return FunctionAST
     */
    public function parse(string $code): FunctionAST
    {
        return new FunctionAST();
    }
}
