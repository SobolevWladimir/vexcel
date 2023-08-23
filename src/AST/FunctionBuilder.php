<?php

namespace SobolevWladimir\Vexcel\AST;

use SobolevWladimir\Vexcel\AST\Function\AbstractFunction;
use SobolevWladimir\Vexcel\AST\Function\FunFalse;
use SobolevWladimir\Vexcel\AST\Function\Funif;
use SobolevWladimir\Vexcel\AST\Function\FunNot;
use SobolevWladimir\Vexcel\AST\Function\FunTrue;
use SobolevWladimir\Vexcel\AST\Function\RoundDOWN;
use SobolevWladimir\Vexcel\AST\Function\RoundUP;
use SobolevWladimir\Vexcel\Exceptions\ASTException;
use SobolevWladimir\Vexcel\Exceptions\SyntaxError;
use SobolevWladimir\Vexcel\Lexer\Token;

class FunctionBuilder
{
    /** @var array<string, string> */
    private array $functions = [
        'ЕСЛИ'        => Funif::class,
        'НЕ'          => FunNot::class,
        'ИСТИНА'      => FunTrue::class,
        'ЛОЖЬ'        => FunFalse::class,
        'ОКРУГЛВВЕРХ' => RoundUP::class,
        'ОКРУГЛВНИЗ'  => RoundDOWN::class,
    ];

    /**
     * @param Token        $token
     * @param Expression[] $args
     *
     * @return AbstractFunction
     *
     * @throws SyntaxError
     */
    public function build(Token $token, array $args): AbstractFunction
    {
        $funname = $token->value;

        if (\array_key_exists($funname, $this->functions)) {
            $className = $this->functions[$funname];

            $classObje = new $className($token, $args);

            if ($classObje instanceof AbstractFunction) {
                return $classObje;
            }
            throw new ASTException("Функция {$funname} не является  AbstractFunction");
        }
        throw new SyntaxError('Неизвестная функция: ' . $token->value, $token);
    }
}
