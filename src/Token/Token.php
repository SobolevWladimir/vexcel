<?php

namespace Wladimir\ParserExcel\Token;

class Token
{
    private int $position = 0;
    /** @val TokenValue[] $tokens*/
    private array $tokens = [];

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
            $this->position++;
        }
        return null;
    }

    private function getCurrentSymbol(): string
    {
        return $this->text[$this->position];
    }

    private function isNumber(): bool
    {
        if (preg_match("/[0-9]/i", $this->getCurrentSymbol())) {
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

    private function isSeparator(): bool
    {
        $currentSymbol  = $this->getCurrentSymbol();
        if ($currentSymbol === ' ') {
            return true;
        }
        if ($currentSymbol  === ";") {
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
        $this->position++;
        if ($hasDot) {
            return new TokenValue(ValueType::Float, (float)$result);
        }
        return new TokenValue(ValueType::Int, (int)$result);
    }
}
