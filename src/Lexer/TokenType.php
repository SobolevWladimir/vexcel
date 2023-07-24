<?php

namespace Wladimir\ParserExcel\Lexer;

enum TokenType: string
{
    case String = 'string';
    case Int = 'int';
    case Float = 'float';
    case Function = 'function';
    case ConditionalOperator = 'conditionalOperator';
    case Operator = 'operator';
    case Variable = 'variable';
    case Parentheses = 'parentheses';
    case Separator = 'separator';
}
