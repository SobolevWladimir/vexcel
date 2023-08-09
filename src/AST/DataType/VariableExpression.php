<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class VariableExpression extends DataType
{
    public function __construct(private string $identifier, protected Token $token)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type'       => 'VariableExpression',
            'identifier' => $this->identifier,
            'token'      => $this->token,
        ];
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        if ($repository === null) {
            throw new UnsupportedError('Не указан репозиторий для получения значения для переменной');
        }

        return $repository->getValueByIdentifier($this->identifier);
    }
}
