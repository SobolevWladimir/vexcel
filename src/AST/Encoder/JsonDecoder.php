<?php

namespace Wladimir\ParserExcel\AST\Encoder;

use Wladimir\ParserExcel\AST\DataType\FloatExpression;
use Wladimir\ParserExcel\AST\DataType\IntExpression;
use Wladimir\ParserExcel\AST\DataType\StringExpression;
use Wladimir\ParserExcel\AST\DataType\VariableExpression;
use Wladimir\ParserExcel\AST\Expression;
use Wladimir\ParserExcel\AST\Function\AbstractFunction;
use Wladimir\ParserExcel\AST\FunctionBuilder;
use Wladimir\ParserExcel\AST\Operator\Operator;

class JsonDecoder implements JsonDecoderInterface
{
    public function __construct(protected FunctionBuilder $functionBuilder  = new FunctionBuilder())
    {
    }

    public function decode(JsonData $data): Expression
    {
        switch ($data->getType()) {
            case 'int':
                return $this->decodeInt($data);

            case 'float':
                return $this->decodeFloat($data);

            case 'string':
                return $this->decodeString($data);
        }

        throw new EncoderException(
            "Не удалось преорозовать из json в выражение тип {$data->getType()}. Такой тип выражения система не знает",
            400
        );
    }

    protected function decodeInt(JsonData $data): IntExpression
    {
        return new IntExpression($data->getToken());
    }

    protected function decodeFloat(JsonData $data): FloatExpression
    {
        return new FloatExpression($data->getToken());
    }

    protected function decodeString(JsonData $data): StringExpression
    {
        return new StringExpression($data->getToken());
    }

    protected function decodeVariable(JsonData $data): VariableExpression
    {
        $props = $data->getProps();
        $identifier = (string)$props['identifier'];

        return new VariableExpression($identifier, $data->getToken());
    }

    protected function decodeFunction(JsonData $data): AbstractFunction
    {
        $props = $data->getProps();
        $args = [];

        foreach ($props['args'] as $argJson) {
            $argData = JsonData::fromJson($argJson);
            $args[] = $this->decode($argData);
        }

        return $this->functionBuilder->build($data->getToken(), $args);
    }

    protected function decodeOperator(JsonData $data): Operator
    {
        $props = $data->getProps();
        $leftExpressionData = JsonData::fromJson($props['leftExpression']);
        $rightExpressionData = JsonData::fromJson($props['rightExpression']);
        $leftExpression = $this->decode($leftExpressionData);
        $rightExpression = $this->decode($rightExpressionData);

        return new Operator($data->getToken(), $leftExpression, $rightExpression);
    }
}
