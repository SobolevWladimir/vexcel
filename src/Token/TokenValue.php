<?php

namespace Wladimir\ParserExcel\Token;

class TokenValue implements \JsonSerializable
{
    public function __construct(
        public ValueType $type,
        public mixed $value,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
        'type' => $this->type,
        'value' => $this->value,
        ];
    }
}
