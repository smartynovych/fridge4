<?php

namespace App\GraphQL\Type;

use GraphQL\Language\AST\Node;

/**
 * Class Date
 * @package App\GraphQL\Type
 */
class Date
{
    /**
     * @var string
     */
    public $name = 'DateType';

    /**
     * @param \DateTime $value
     *
     * @return string
     */
    public static function serialize(\DateTime $value)
    {
        return $value->format('d.m.Y');
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
