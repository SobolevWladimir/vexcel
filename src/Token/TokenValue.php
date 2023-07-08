<?php

namespace Wladimir\ParserExcel\Token;

class TokenValue implements \JsonSerializable
{
    public function __construct(
        public ValueType $type,
        public mixed $value,
        public int $row = 0,
        public int $column = 0,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => $this->type,
        'value' => $this->value,
        'row' => $this->row,
        'column' => $this->column,
        ];
    }
}
