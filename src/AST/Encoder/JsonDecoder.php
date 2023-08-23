<?php

namespace SobolevWladimir\Vexcel\AST\Encoder;

use SobolevWladimir\Vexcel\AST\DataType\FloatExpression;
use SobolevWladimir\Vexcel\AST\DataType\IntExpression;
use SobolevWladimir\Vexcel\AST\DataType\StringExpression;
use SobolevWladimir\Vexcel\AST\DataType\VariableExpression;
use SobolevWladimir\Vexcel\AST\Expression;
use SobolevWladimir\Vexcel\AST\Function\AbstractFunction;
use SobolevWladimir\Vexcel\AST\FunctionBuilder;
use SobolevWladimir\Vexcel\AST\Operator\Operator;

class JsonDecoder implements JsonDecoderInterface
{
    public function __construct(protected FunctionBuilder $functionBuilder = new FunctionBuilder())
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

            case 'variable':
                return $this->decodeVariable($data);

            case 'function':
                return $this->decodeFunction($data);

            case 'operator':
                return $this->decodeOperator($data);
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
