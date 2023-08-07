<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

interface Expression extends \JsonSerializable
{
    /**
     * /**
     * Подсчитывает  и возвращает значение.
     *
     * @param VariableRepository $repository
     *
     * @return mixed
     */
    public function calculate(?ValueRepositoryInterface $repository = null): mixed;
}
