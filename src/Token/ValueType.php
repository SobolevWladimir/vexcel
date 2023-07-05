<?php

namespace Wladimir\ParserExcel\Token;

enum ValueType: string
{
    case String = 'string';
    case Int = 'int';
    case Float = 'float';
    case ConditionalOperator = 'conditionalOperator';
    case Operator = 'operator';
    case Variable = 'variable';
    case Function = 'function';
    case EndFunction = 'endfunction';
    case Separator = 'separator';
}
