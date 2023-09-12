<?php

namespace Tests\Data;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class ValueRepositoryFake implements ValueRepositoryInterface
{
    /** @var array<string, int> */
    private array $variables = [
        'ОДИН'    => 1,
        'ДВА'     => 2,
        'ТРИ'     => 3,
        'ПЕР ТРИ' => 3,
        'ЧЕТЫРЕ'  => 4,
        'ПЯТЬ'    => 5,
        'ШЕСТЬ'   => 6,
    ];

    public function getValueByIdentifier(string $identificator): mixed
    {
        if (!\array_key_exists($identificator, $this->variables)) {
            throw new \Exception("Not exist {$identificator}");
        }

        return $this->variables[$identificator];
    }
}
