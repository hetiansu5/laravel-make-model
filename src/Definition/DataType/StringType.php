<?php

namespace MakeModel\Definition\DataType;

use MakeModel\Traits\InstanceTrait;

class StringType implements TypeInterface
{
    use InstanceTrait;

    public function getType()
    {
        return Type::STRING;
    }

    public function toString()
    {
        return 'string';
    }

}
