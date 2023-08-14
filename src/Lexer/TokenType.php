<?php

namespace Wladimir\ParserExcel\Lexer;

enum TokenType: string
{
    case String = 'string';

    case Int = 'int';

    case Float = 'float';

    case Function = 'function';

    case ConditionalOperator = 'conditionalOperator';

    case BinaryOperator = 'operator';

    case Variable = 'variable';

    case Parentheses = 'parentheses';

    case Separator = 'separator';

    case Unknown = 'unknown';

    public static function tryFromName(string $name): ?TokenType
    {
        switch ($name) {
            case 'string':
                return self::String;

            case 'int':
                return self::Int;

            case 'float':
                return self::Float;

            case 'function':
                return self::Function;

            case 'conditionalOperator':
                return self::ConditionalOperator;

            case 'operator':
                return self::BinaryOperator;

            case 'variable':
                return self::Variable;

            case 'parentheses':
                return self::Parentheses;

            case 'separator':
                return self::Separator;

            case 'unknown':
                return self::Unknown;
        }

        return null;
    }
}
