<?php

namespace SobolevWladimir\Vexcel\AST\Encoder;

use SobolevWladimir\Vexcel\AST\Expression;

interface EncoderInterface
{
    public function encode(Expression $expression): string;
}
