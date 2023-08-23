<?php

namespace SobolevWladimir\Vexcel\AST;

use SobolevWladimir\Vexcel\AST\Encoder\JsonData;
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

interface Expression
{
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
