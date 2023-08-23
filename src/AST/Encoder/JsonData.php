<?php

namespace SobolevWladimir\Vexcel\AST\Encoder;

use SobolevWladimir\Vexcel\Lexer\Token;

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

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): mixed
    {
        return [
            'type'  => $this->type,
            'token' => $this->token,
            'props' => $this->props,
        ];
    }

    /**
     * @param mixed[] $json
     *
     * @return JsonData
     */
    public static function fromJson(array $json): self
    {
        $token = Token::fromJson($json['token']);

        return new self($json['type'], $token, $json['props']);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed[]
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * @param mixed[] $props
     */
    public function setProps(array $props): void
    {
        $this->props = $props;
    }
}
