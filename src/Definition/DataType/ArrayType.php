<?php

namespace MakeModel\Definition\DataType;

use MakeModel\Traits\InstanceTrait;

class ArrayType implements TypeInterface
{

    use InstanceTrait;

    public function getType()
    {
        return Type::ARR;
    }

    public function toString()
    {
        return 'array';
    }

}
