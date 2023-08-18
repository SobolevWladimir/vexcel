<?php 
namespace Wladimir\ParserExcel\AST\Encoder;

use Wladimir\ParserExcel\AST\Expression;

interface EncoderInterface {
  public function encode(Expression $expression):string;

}
