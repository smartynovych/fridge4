<?php

namespace App\GraphQL\Type;

use GraphQL\Language\AST\Node;

/**
 * Class DateTime
 * @package App\GraphQL\Type
 */
class DateTime
{
    /**
     * @var string
     */
    public $name = 'DateTimeType';

    /**
     * @param \DateTime $value
     *
     * @return string
     */
    public static function serialize(\DateTime $value)
    {
        return $value->format('d.m.Y H:i:s');
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function parseValue($value)
    {
        return new \DateTime($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     */
    public static function parseLiteral($valueNode)
    {
        return new \DateTime($valueNode->value);
    }
}
