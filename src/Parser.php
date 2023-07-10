<?php

namespace Wladimir\ParserExcel;

use Wladimir\ParserExcel\Repository\VariableRepository;

class Parser
{
    public function __construct(
        private VariableRepository $repository,
        private array $operators = [],
        private array $conditionOperators = [],
        private array $functions = [],
    ) {
    }
}
