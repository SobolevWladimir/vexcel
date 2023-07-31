<?php

namespace Wladimir\ParserExcel\Parser;

use Exception;
use PharIo\Manifest\Extension;
use Wladimir\ParserExcel\AST\DataType\IntExpression;
use Wladimir\ParserExcel\AST\DataType\StringExpression;
use Wladimir\ParserExcel\AST\DataType\VariableExpression;
use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\AST\FunctionAST;
use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\Lexer\Lexer;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Lexer\TokenType;
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
        $tokens = $this->lexer->getAllTokens();
        return new FunctionAST();
    }


    private function nextToken(): void
    {
        $this->currentPosition++;
    }

    /** получить приоритет текущего оператора  */
    private function getTokPrecedence(): int
    {
        return 0;
    }

    private function getCurrentToken(): Token
    {
        return $this->tokens[$this->currentPosition];
    }

    /** Распарсить текущий токен  */
    private function parsePrimary(): ?Expression
    {
        $token = $this->getCurrentToken();
        switch ($token->type) {
            case TokenType::String:
                return $this->parseStringExpr();
            case TokenType::Int:
                return $this->parseIntExpr();
            case TokenType::Float:
                return $this->parseFloatExpr();
            case TokenType::Function:
                break;
            case TokenType::Variable:
                return $this->parseVariableExpr();
            case TokenType::Parentheses:
                if ($token->value === "(") {
                    return $this->parseParenthesesExpr();
                }
                break;
        }

        $this->logError('Не ожидаенный тип токена', $token);
        return null;
    }

    private function parseStringExpr(): StringExpression
    {
        $currentToken =  $this->getCurrentToken();
        $this->nextToken();
        return new StringExpression($currentToken);
    }

    private function parseIntExpr(): IntExpression
    {

        $currentToken =  $this->getCurrentToken();
        $this->nextToken();
        return new IntExpression($currentToken);
    }

    private function parseFloatExpr(): IntExpression
    {

        $currentToken =  $this->getCurrentToken();
        $this->nextToken();
        return new IntExpression($currentToken);
    }

    private function parseVariableExpr(): VariableExpression
    {
        $currentToken =  $this->getCurrentToken();
        $identifier = $this->repository->getIdentifierByName((string)$currentToken->value);
        $this->nextToken();
        return new VariableExpression($identifier, $currentToken);
    }

    private function parseParenthesesExpr(): ?Expression
    {
        $this->nextToken();
        $expression = $this->parseExpression();
        if (!$expression) {
            return null;
        }
        $token = $this->getCurrentToken();
        if ($token->type != TokenType::Parentheses && $token->value !== ")") {
            $this->logError('Ожидается )', $token);
            return null;
        }
        return $expression;
    }


    private function parseExpression(): ?Expression
    {
        throw new Exception('пока не сделал');
    }

    private function logError(string $error, Token $token)
    {

        throw new Exception($error);
    }
}
