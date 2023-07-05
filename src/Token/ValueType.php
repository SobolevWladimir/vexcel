<?php

namespace Wladimir\ParserExcel\Token;

enum ValueType: string
{
    case String = 'string';
    case Int = 'int';
    case Float = 'float';
    case Function = 'function';
    case Operator = 'operator';
}
