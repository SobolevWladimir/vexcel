<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\AST\Function\AbstractFunction;
use Wladimir\ParserExcel\AST\Function\Funif;
use Wladimir\ParserExcel\AST\Function\FunNot;
use Wladimir\ParserExcel\AST\Function\FunTrue;
use Wladimir\ParserExcel\AST\Function\FunFalse;
use Wladimir\ParserExcel\AST\Function\RoundUP;
use Wladimir\ParserExcel\AST\Function\RoundDOWN;
use Wladimir\ParserExcel\Exceptions\ASTException;
use Wladimir\ParserExcel\Exceptions\SyntaxError;
use Wladimir\ParserExcel\Lexer\Token;

class FunctionBuilder
{
    /** @var array<string, string> */
    private array $functions = [
    'ЕСЛИ' => Funif::class,
    'НЕ' => FunNot::class,
    'ИСТИНА' => FunTrue::class,
    'ЛОЖЬ' => FunFalse::class,
    'ОКРУГЛВВЕРХ' => RoundUP::class,
    'ОКРУГЛВНИЗ' => RoundDOWN::class,


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
        $funname  = $token->value;
        if (array_key_exists($funname, $this->functions)) {
            $className  = $this->functions[$funname];

            $classObje  = new $className($token, $args);
            if ($classObje instanceof AbstractFunction) {
                  return $classObje;
            }
            throw new ASTException("Функция $funname не является  AbstractFunction");
        }
        throw new SyntaxError('Неизвестная функция: ' . $token->value, $token);
    }
}
