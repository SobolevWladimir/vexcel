<?php

namespace Wladimir\ParserExcel\Parser;

use Wladimir\ParserExcel\AST\FunctionAST;
use Wladimir\ParserExcel\Lexer\Lexer;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\ParserInterface;
use Wladimir\ParserExcel\Repository\EmptyVariableRepository;
use Wladimir\ParserExcel\Repository\VariableRepositoryInterface;

class Parser implements ParserInterface
{
    // приоритет операторов
    const BINOP_PRECEDENCE = [
    '-' => 20,
    '+' => 20,
    '*' => 40,
    '/' => 40,
    '^' => 80,
    ];
    private $tokens = [];
    private $currentPosition = 0;
    public function __construct(
        private Lexer $lexer = new Lexer(),
        private VariableRepositoryInterface $repository = new EmptyVariableRepository(),
    ) {
    }
    /**
     * @param string $code
     * @return FunctionAST
     */
    public function parse(string $code): FunctionAST
    {
        $this->lexer->setCode($code);
        $this->tokens = $this->lexer->getAllTokens();
        return new FunctionAST();
    }

    private function nextToken(): void
    {
        $this->currentPosition++;
    }

    private function getTokPrecedence(): int
    {
        return 0;
    }

    private function getCurrentToken(): Token
    {
        return $this->tokens[$this->currentPosition];
    }
}
