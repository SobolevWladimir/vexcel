<?php

namespace SobolevWladimir\Vexcel\AST\Encoder;

use SobolevWladimir\Vexcel\AST\Expression;

interface JsonDecoderInterface
{
    public function decode(JsonData $data): Expression;
}
