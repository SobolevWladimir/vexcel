<?php

namespace Wladimir\ParserExcel\AST\Encoder;

use Wladimir\ParserExcel\Lexer\Token;

class JsonData implements \JsonSerializable
{
    /**
     * @param string  $type
     * @param Token   $token
     * @param mixed[] $props
     */
    public function __construct(private string $type, private Token $token, private array $props = [])
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type'  => $this->type,
            'token' => $this->token,
            'props' => $this->props,
        ];
    }
}
