<?php

namespace MakeModel\Definition\DataType;

use MakeModel\Traits\InstanceTrait;

class FloatType implements TypeInterface
{
    use InstanceTrait;

    public function getType()
    {
        return Type::FLOAT;
    }

    public function toString()
    {
        return 'float';
    }

}
