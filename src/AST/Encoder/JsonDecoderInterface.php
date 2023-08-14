<?php

namespace Wladimir\ParserExcel\AST\Encoder;

use Wladimir\ParserExcel\AST\Expression;

interface JsonDecoderInterface
{
    public function decode(JsonData $data): Expression;
}
