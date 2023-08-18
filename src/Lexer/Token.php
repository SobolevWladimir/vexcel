<?php

namespace Wladimir\ParserExcel\Lexer;

class Token implements \JsonSerializable
{
    public function __construct(
        public TokenType $type,
        public mixed $value,
        public int $row = 0,
        public int $column = 0,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type'   => $this->type,
            'value'  => $this->value,
            'row'    => $this->row,
            'column' => $this->column,
        ];
    }

    /**
     * @param mixed[] $json
     *
     * @return Token
     */
    public static function fromJson(array $json): self
    {
        $type = TokenType::tryFromName($json['type']);

        if (!$type) {
            throw new \Exception('Не удалось преобразовать из json в TokenType: ' . $json['type']);
        }

        return new self(
            $type,
            $json['value'],
            (int)$json['row'],
            (int)$json['column'],
        );
    }
}
