<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

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
