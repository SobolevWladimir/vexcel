<?php

namespace Wladimir\ParserExcel\Lexer;

use Wladimir\ParserExcel\Exceptions\SyntaxError;

class Lexer
{
    private int $position = 0;

    private int $column = 0;

    private int $row = 0;
    /** @val Token[] $tokens*/
    private array $tokens = [];

    private array $conditionalOperators  = ['!', '=', '<', '>'];

    private array $operators  = ['*', '/', '+', '-', '^'];
    private string $text;

    public function __construct()
    {
    }

    public function setCode($code): void
    {
        $this->text = $code;
        $this->tokens = $this->parse();
        $this->position = 0;
        $this->column = 0;
        $this->row = 0;
    }

    /**
     * @return Token[]
     */
    public function getAllTokens(): array
    {
        return $this->tokens;
    }

    /** @return Token[]  */
    private function parse(): array
    {
        $result  = [];
        $this->position = 0;
        while (!$this->isEnd()) {
            $value  = $this->readNext();
            if ($value !== null) {
                $result[] = $value;
            }
        }
        return $result;
    }
    private function readNext(): ?Token
    {
        while (!$this->isEnd()) {
            if ($this->isEscaped()) {
                return $this->readString();
            }
            if ($this->isNumber()) {
                return $this->readNumber();
            }
            if ($this->isConditionalOperator()) {
                return $this->readConditionalOperator();
            }
            if ($this->isOperator()) {
                return $this->readOperator();
            }
            if ($this->isVariable()) {
                return $this->readVariable();
            }
             $currentSymbol = $this->getCurrentSymbol();
            if ($currentSymbol  === ";") {
                $result = $this->getToken(TokenType::Separator, $currentSymbol, $this->column);
                $this->nextSymbol();
                return $result;
            }

            if ($currentSymbol  === "(" || $currentSymbol  === ")") {
                $result  = $this->getToken(TokenType::Parentheses, $currentSymbol, $this->column);
                $this->nextSymbol();
                return $result;
            }
            if ($currentSymbol !== " "  && $currentSymbol !== "" && !$this->isNewLine()) {
                $symbol = $currentSymbol;
                throw new SyntaxError("Неизвестный символ: $symbol", 400, $this->row, $this->column);
            }

            $this->nextSymbol();
        }
        return null;
    }

    private function getToken(
        TokenType $type,
        mixed $value,
        int $column ,
    ): Token {
        return new Token($type, $value, $this->row, $column);
    }

    private function getCurrentSymbol(): string
    {
        return mb_substr($this->text, $this->position, 1);
    }

    private function nextSymbol(): void
    {
        $this->position++;
        if ($this->isNewLine()) {
            $this->position++;
            $this->column = 0;
            $this->row++;
        } else {
            $this->column++;
        }
    }

    private function isNewLine(): bool
    {
        $currentSymbol = $this->getCurrentSymbol();
        if ($currentSymbol == "\n\r" || $currentSymbol == "\n" || $currentSymbol == "\r") {
            return true;
        }
        return false;
    }

    private function isNumber(): bool
    {
        if (preg_match("/[0-9]/i", $this->getCurrentSymbol())) {
            return true;
        }
        return false;
    }

    private function isVariable(): bool
    {
        if (preg_match("/[a-zа-я._]/i", $this->getCurrentSymbol())) {
            return true;
        }
        return false;
    }

    private function isConditionalOperator(): bool
    {
        if (array_search($this->getCurrentSymbol(), $this->conditionalOperators) !== false) {
            return true;
        }
        return false;
    }

    private function isOperator(): bool
    {
        if (array_search($this->getCurrentSymbol(), $this->operators) !== false) {
            return true;
        }
        return false;
    }


    private function isEscaped(): bool
    {
        $currentSymbol  = $this->getCurrentSymbol();
        if ($currentSymbol === '"') {
            return true;
        }
        if ($currentSymbol  === "'") {
            return true;
        }
        return false;
    }

    private function isEnd(): bool
    {
        return $this->position >= strlen($this->text);
    }

    private function readString(): Token
    {
        $startColumn = $this->column;
        $startRow = $this->row;
        if ($this->isEscaped()) {
            $this->nextSymbol();
        }
        $result = "";
        while (!$this->isEscaped() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->nextSymbol();
        }
        $this->nextSymbol();
        return new Token(TokenType::String, $result, $startRow, $startColumn);
    }

    private function readNumber(): Token
    {
        $hasDot = false;
        $result = "";
        $startColumn = $this->column;
        $startRow = $this->row;

        while ($this->isNumber() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->nextSymbol();
            $next  = $this->getCurrentSymbol();
            if ($next === "." || $next === ",") {
                $hasDot = true;
                $result .= $next;
                $this->nextSymbol();
            }
        }
        if ($hasDot) {
            return new Token(TokenType::Float, (float)$result, $startRow, $startColumn);
        }
        return new Token(TokenType::Int, (int)$result, $startRow, $startColumn);
    }

    private function readConditionalOperator(): Token
    {
        $result = "";
        $startColumn = $this->column;
        $startRow = $this->row;
        while ($this->isConditionalOperator() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->nextSymbol();
        }
        return new Token(TokenType::ConditionalOperator, $result, $startRow, $startColumn);
    }

    private function readOperator(): Token
    {
        $result = "";
        $startColumn = $this->column;
        $startRow = $this->row;

        while ($this->isOperator() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->nextSymbol();
        }
        return new Token(TokenType::BinaryOperator, $result, $startRow, $startColumn);
    }

    private function readVariable(): Token
    {
        $result = "";
        $isFunction  = false;
        $startColumnt = $this->column;
        $startRow = $this->row;
        while (!$this->isEnd()) {
            $currentSymbol  = $this->getCurrentSymbol();
            if ($currentSymbol === "(") {
                $isFunction = true;
                $this->nextSymbol();
                break;
            }
            if (!$this->isVariable()) {
                break;
            }

            $result .= $currentSymbol;
            $this->nextSymbol();
        }
        if ($isFunction) {
            return new Token(TokenType::Function, $result, $startRow, $startColumnt);
        }
        return new Token(TokenType::Variable, $result, $startRow, $startColumnt);
    }
}
