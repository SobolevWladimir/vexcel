<?php

namespace Wladimir\ParserExcel\Parser;

use Wladimir\ParserExcel\AST\FunctionAST;
use Wladimir\ParserExcel\Lexer\Lexer;
use Wladimir\ParserExcel\ParserInterface;
use Wladimir\ParserExcel\Repository\EmptyVariableRepository;
use Wladimir\ParserExcel\Repository\VariableRepositoryInterface;

class Parser implements ParserInterface
{
    private VariableRepositoryInterface $repository;
    public function __construct(
        ?VariableRepositoryInterface $repository,
        private ?Lexer $lexer = null,
    ) {
        if ($repository) {
            $this->repository = $repository;
        } else {
            $this->repository  = new EmptyVariableRepository();
        }
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
