<?php

namespace Wladimir\ParserExcel\AST\DataType;

use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\Exceptions\UnsupportedError;
use Wladimir\ParserExcel\Lexer\Token;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class VariableExpression extends DataType
{
    public function __construct(private string $identifier, protected Token $token)
    {
    }

    public function getJsonData(): JsonData
    {
        return new JsonData('variable', $this->token, ['identifier' => $this->identifier]);
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        if ($repository === null) {
            throw new UnsupportedError('Не указан репозиторий для получения значения для переменной');
        }

        return $repository->getValueByIdentifier($this->identifier);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
