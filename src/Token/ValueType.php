<?php

namespace Wladimir\ParserExcel\Token;

enum ValueType: string
{
    case String = 'string';
    case Int = 'int';
    case Float = 'float';
    case Function = 'function';
    case ConditionalOperator = 'conditionalOperator';
    case Operator = 'operator';
    case Variable = 'variable';
}
