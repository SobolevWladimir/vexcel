<?php

namespace SobolevWladimir\Vexcel\AST;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

interface Expression
{
    /**
     * Вернуть список используемых переменных.
     *
     * @return string[]
     */
    public function getUsedVariables(): array;

    /**
     * /**
     * Подсчитывает  и возвращает значение.
     *
     * @param ValueRepositoryInterface $repository
     *
     * @return mixed
     */
    public function calculate(?ValueRepositoryInterface $repository = null): mixed;

    /**
     *  Вернуть данные для преобразования в json.
     *
     * @return JsonData  */
    public function getJsonData(): JsonData;
}
