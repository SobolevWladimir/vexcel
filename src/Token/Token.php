<?php

namespace Wladimir\ParserExcel\Token;

class Token
{
    private int $position = 0;
    /** @val TokenValue[] $tokens*/
    private array $tokens = [];

    private array $conditionalOperators  = ['!', '=', '<', '>'];

    private array $operators  = ['*', '/', '+', '-', '^'];

    public function __construct(private string $text)
    {
    }

    /**
     * @return TokenValue[]
     */
    public function getTokens(): array
    {
        if (count($this->tokens) == 0) {
            $this->tokens = $this->parse();
        }
        return $this->tokens;
    }

    /** @return TokenValue[]  */
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
    private function readNext(): ?TokenValue
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
                $this->position++;
                return new TokenValue(ValueType::Separator, $currentSymbol);
            }

            if ($currentSymbol  === ")") {
                $this->position++;
                return new TokenValue(ValueType::EndFunction, $currentSymbol);
            }

            $this->position++;
        }
        return null;
    }

    private function getCurrentSymbol(): string
    {
        return mb_substr($this->text, $this->position, 1);
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

    private function readString(): TokenValue
    {
        if ($this->isEscaped()) {
            $this->position++;
        }
        $result = "";
        while (!$this->isEscaped() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->position++;
        }
        $this->position++;
        return new TokenValue(ValueType::String, $result);
    }

    private function readNumber(): TokenValue
    {
        $hasDot = false;
        $result = "";

        while ($this->isNumber() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->position++;
            $next  = $this->getCurrentSymbol();
            if ($next === "." || $next === ",") {
                $hasDot = true;
                $result .= $next;
                $this->position++;
            }
        }
        if ($hasDot) {
            return new TokenValue(ValueType::Float, (float)$result);
        }
        return new TokenValue(ValueType::Int, (int)$result);
    }

    private function readConditionalOperator(): TokenValue
    {
        $result = "";

        while ($this->isConditionalOperator() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->position++;
        }
        return new TokenValue(ValueType::ConditionalOperator, $result);
    }

    private function readOperator(): TokenValue
    {
        $result = "";

        while ($this->isOperator() &&  !$this->isEnd()) {
            $result .= $this->getCurrentSymbol();
            $this->position++;
        }
        return new TokenValue(ValueType::Operator, $result);
    }

    private function readVariable(): TokenValue
    {
        $result = "";
        $isFunction  = false;
        while (!$this->isEnd()) {
            $currentSymbol  = $this->getCurrentSymbol();
            if ($currentSymbol === "(") {
                $isFunction = true;
                break;
            }
            if (!$this->isVariable()) {
                break;
            }

            $result .= $currentSymbol;
            $this->position++;
        }
        if ($isFunction) {
            return new TokenValue(ValueType::Function, $result);
        }
        return new TokenValue(ValueType::Variable, $result);
    }
}
