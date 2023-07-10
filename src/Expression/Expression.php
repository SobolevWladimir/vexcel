<?php

namespace Wladimir\ParserExcel\Expression;

use Wladimir\ParserExcel\Repository\VariableRepository;

interface Expression
{
    /**
  * Подсчитывает  и возвращает значение
     * @param VariableRepository $repository
     * @return mixed
     */
    public function calculate(VariableRepository $repository): mixed;
}
