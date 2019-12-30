<?php

namespace MakeModel\Definition\DataType;

use MakeModel\Traits\InstanceTrait;

class IntType implements TypeInterface
{
    use InstanceTrait;

    public function getType()
    {
        return Type::INT;
    }

    public function toString()
    {
        return 'int';
    }

}
