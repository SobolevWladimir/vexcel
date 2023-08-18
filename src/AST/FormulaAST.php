<?php

namespace Wladimir\ParserExcel\AST;

use Wladimir\ParserExcel\AST\Encoder\EncoderInterface;
use Wladimir\ParserExcel\AST\Encoder\JsonData;
use Wladimir\ParserExcel\AST\Encoder\JsonDecoder;
use Wladimir\ParserExcel\AST\Encoder\VexcelEncoder;
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class FormulaAST implements \JsonSerializable
{
    public function __construct(public ?Expression $body)
    {
    }

    public function calculate(?ValueRepositoryInterface $repository = null): mixed
    {
        if ($this->body == null) {
            return null;
        }

        return $this->body->calculate($repository);
    }

    public function jsonSerialize(): mixed
    {
        $body = null;

        if ($this->body) {
            $body = $this->body->getJsonData();
        }

        return [
            'body' => $body,
        ];
    }

    public static function fromJson(mixed $json, JsonDecoder $jsonDecoder = new JsonDecoder()): self
    {
        $bodyData = JsonData::fromJson($json['body']);
        $body = $jsonDecoder->decode($bodyData);

        return new self($body);
    }

    public function toCode(EncoderInterface $encoder = new VexcelEncoder()): string
    {
    if($this->body == null){
      return "";
    }
        return $encoder->encode($this->body);
    }
}
