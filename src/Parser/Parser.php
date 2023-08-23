<?php

namespace SobolevWladimir\Vexcel\Parser;

use SobolevWladimir\Vexcel\AST\DataType\FloatExpression;
use SobolevWladimir\Vexcel\AST\DataType\IntExpression;
use SobolevWladimir\Vexcel\AST\DataType\StringExpression;
use SobolevWladimir\Vexcel\AST\DataType\VariableExpression;
use SobolevWladimir\Vexcel\AST\Expression;
use SobolevWladimir\Vexcel\AST\FormulaAST;
use SobolevWladimir\Vexcel\AST\Function\AbstractFunction;
use SobolevWladimir\Vexcel\AST\FunctionBuilder;
use SobolevWladimir\Vexcel\AST\Operator\Operator;
use SobolevWladimir\Vexcel\Exceptions\SyntaxError;
use SobolevWladimir\Vexcel\Lexer\Lexer;
use SobolevWladimir\Vexcel\Lexer\Token;
use SobolevWladimir\Vexcel\Lexer\TokenType;
use SobolevWladimir\Vexcel\ParserInterface;
use SobolevWladimir\Vexcel\Repository\EmptyVariableRepository;
use SobolevWladimir\Vexcel\Repository\VariableRepositoryInterface;

class Parser implements ParserInterface
{
    // приоритет операторов
    public const BINOP_PRECEDENCE = [
        '-' => 20,
        '+' => 20,
        '*' => 40,
        '/' => 40,
        '^' => 80,
        '=' => 20,
        '<' => 20,
        '>' => 20,
    ];

    /** @var Token[] */
    private $tokens = [];
    private int $currentPosition = 0;

    public function __construct(
        private Lexer $lexer = new Lexer(),
        private VariableRepositoryInterface $repository = new EmptyVariableRepository(),
        private FunctionBuilder $functionBuilder = new FunctionBuilder(),
    ) {
    }

    /**
     * @param string $code
     *
     * @return FormulaAST
     */
    public function parse(string $code): FormulaAST
    {
        $this->lexer->setCode($code);
        $this->tokens = $this->lexer->getAllTokens();
        $body = $this->parseExpression();

        return new FormulaAST($body);
    }

    private function nextToken(): void
    {
        $this->currentPosition++;
    }

    /**
     * получить приоритет текущего оператора.
     */
    private function getTokPrecedence(Token $operatorToken): int
    {
        $operator = (string)$operatorToken->value;

        if (!\array_key_exists($operator, self::BINOP_PRECEDENCE)) {
            return -1;
        }

        return self::BINOP_PRECEDENCE[$operator];
    }

    private function isEnd(): bool
    {
        if ($this->currentPosition >= \count($this->tokens)) {
            return true;
        }

        return false;
    }

    private function getCurrentToken(): ?Token
    {
        if ($this->currentPosition >= \count($this->tokens)) {
            return null;
        }

        return $this->tokens[$this->currentPosition];
    }

    /**
     * Распарсить текущий токен.
     */
    private function parsePrimary(): ?Expression
    {
        $token = $this->getCurrentToken();

        if (!$token) {
            return null;
        }

        switch ($token->type) {
            case TokenType::String:
                return $this->parseStringExpr($token);

            case TokenType::Int:
                return $this->parseIntExpr($token);

            case TokenType::Float:
                return $this->parseFloatExpr($token);

            case TokenType::Function:
                return $this->parseFuntion($token);

            case TokenType::Variable:
                return $this->parseVariableExpr($token);

            case TokenType::Parentheses:
                if ($token->value === '(') {
                    return $this->parseParenthesesExpr();
                }
                break;
        }

        $this->logError('Не ожидаенный тип токена: ' . $token->value, $token);

        return null;
    }

    private function parseStringExpr(Token $currentToken): StringExpression
    {
        $this->nextToken();

        return new StringExpression($currentToken);
    }

    private function parseIntExpr(Token $currentToken): IntExpression
    {
        $this->nextToken();

        return new IntExpression($currentToken);
    }

    private function parseFloatExpr(Token $currentToken): FloatExpression
    {
        $this->nextToken();

        return new FloatExpression($currentToken);
    }

    private function parseVariableExpr(Token $currentToken): VariableExpression
    {
        $identifier = $this->repository->getIdentifierByName((string)$currentToken->value);
        $this->nextToken();

        return new VariableExpression($identifier, $currentToken);
    }

    private function parseFuntion(Token $fun): AbstractFunction
    {
        $this->nextToken();
        $args = [];

        while (!$this->isEnd()) {
            $token = $this->getCurrentToken();

            if ($token === null) {
                break;
            }

            if ($token->type === TokenType::Parentheses && $token->value === ')') {
                $this->nextToken();
                break;
            }

            if ($token->type === TokenType::Separator) {
                $this->nextToken();
                continue;
            }

            $expression = $this->parseExpression();

            if (!$expression) {
                break;
            }
            $args[] = $expression;
            $token = $this->getCurrentToken();

            if ($token == null) {
                $this->logError('Ожидается ")"  в конце ');
                break;
            }

            if ($token->type !== TokenType::Separator && $token->type !== TokenType::Parentheses) {
                $this->logError('Ожидается ")" или ";". Дано: ' . $token->value, $token);
            }
        }

        return $this->functionBuilder->build($fun, $args);
    }

    private function parseParenthesesExpr(): ?Expression
    {
        $this->nextToken();
        $expression = $this->parseExpression();

        if (!$expression) {
            return null;
        }
        $token = $this->getCurrentToken();

        if ($token == null || ($token->type != TokenType::Parentheses && $token->value !== ')')) {
            $this->logError('Ожидается ")"', $token);

            return null;
        }

        return $expression;
    }

    private function parseExpression(): ?Expression
    {
        $lhs = $this->parsePrimary();

        if ($lhs === null) {
            return null;
        }

        return $this->parseBinOpRHS(0, $lhs);
    }

    private function parseBinOpRHS(int $exprPrec, Expression $lhs): ?Expression
    {
        while (true) {
            $operator = $this->getCurrentToken();

            if (!$operator) {
                return $lhs;
            }
            $tokPrec = $this->getTokPrecedence($operator);

            if ($tokPrec < $exprPrec) {
                return $lhs;
            }
            $this->nextToken();
            $rhs = $this->parsePrimary();

            if (!$rhs) {
                return null;
            }
            $nextToken = $this->getCurrentToken();

            if ($nextToken) {
                $nextPrec = $this->getTokPrecedence($nextToken);

                if ($tokPrec < $nextPrec) {
                    $rhs = $this->parseBinOpRHS($tokPrec + 1, $rhs);

                    if (!$rhs) {
                        return null;
                    }
                }
            }

            $lhs = new Operator($operator, $lhs, $rhs);
        }
    }

    private function logError(string $error, ?Token $token = null): void
    {
        throw new SyntaxError($error, $token);
    }
}
