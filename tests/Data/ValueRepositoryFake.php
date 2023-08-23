<?php

namespace Tests\Data;

use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class ValueRepositoryFake implements ValueRepositoryInterface
{
    /** @var array<string, int> */
    private array $variables = [
        'ОДИН'   => 1,
        'ДВА'    => 2,
        'ТРИ'    => 3,
        'ЧЕТЫРЕ' => 4,
        'ПЯТЬ'   => 5,
        'ШЕСТЬ'  => 6,
    ];

    public function getValueByIdentifier(string $identificator): mixed
    {
        return $this->variables[$identificator];
    }
}
