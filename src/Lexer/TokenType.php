<?php

namespace SobolevWladimir\Vexcel\Lexer;

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
        $arr = [
            'string'              => self::String,
            'int'                 => self::Int,
            'float'               => self::Float,
            'function'            => self::Function,
            'conditionalOperator' => self::ConditionalOperator,
            'operator'            => self::BinaryOperator,
            'variable'            => self::Variable,
            'separator'           => self::Separator,
            'unknown'             => self::Unknown,
        ];

        if (\array_key_exists($name, $arr)) {
            return $arr[$name];
        }

        return null;
    }
}
