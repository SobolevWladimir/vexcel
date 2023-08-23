<?php

namespace SobolevWladimir\Vexcel\AST\DataType;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\Exceptions\UnsupportedError;
use SobolevWladimir\Vexcel\Lexer\Token;
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

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
